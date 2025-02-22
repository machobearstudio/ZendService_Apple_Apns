<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendService\Apple\Apns\Client;

use CurlHandle;
use ZendService\Apple\Exception;

/**
 * Apple Push Notification Abstract Client
 */
abstract class AbstractClient
{
    /**
     * APNS URI Constants
     * @var int
     */
    final const SANDBOX_URI = 0;
    final const PRODUCTION_URI = 1;

    /**
     * APNS URIs
     * @var array
     */
    protected array $uris = [
        'https://api.development.push.apple.com',
        'https://api.push.apple.com'
    ];

    /**
     * Is Connected
     * @var boolean
     */
    protected bool $isConnected = false;

    /**
     * the cURL handle
     * @var ?CurlHandle
     */
    protected ?CurlHandle $http2ch = null;

    /**
     * the selected environment
     * @var int
     */
    protected int $environment;

    /**
     * the certificate path
     * @var string
     */
    protected string $certificate;

    /**
     * the certificate passPhrase
     * @var string|null
     */
    protected ?string $passPhrase;

    /**
     * Curl response status
     * @var int
     */
    protected int $responseStatus = 200;

    /**
     * Push response header identifier app-id
     * @var null|string
     */
    protected ?string $responseId = null;

    /**
     * Push response JSON body
     * @var string
     */
    protected string $responseBody = "";

    /**
     * Open Connection to APNS Service
     *
     * @param string|null $passPhrase
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function open(int $environment, string $certificate, string $passPhrase = null): AbstractClient
    {
        if ($this->isConnected) {
            throw new Exception\RuntimeException('Connection has already been opened and must be closed');
        }

        if (!array_key_exists($environment, $this->uris)) {
            throw new Exception\InvalidArgumentException('Environment must be one of PRODUCTION_URI or SANDBOX_URI');
        }

        if (!file_exists($certificate)) {
            throw new Exception\InvalidArgumentException('Certificate must be a valid path to a APNS certificate');
        }

        $this->environment = $environment;
        $this->certificate = $certificate;
        $this->passPhrase = $passPhrase;

        $this->http2ch = curl_init();

        if (false === $this->http2ch) {
            throw new Exception\RuntimeException('Curl session not initialized');
        }

        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }
        curl_setopt($this->http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        $this->isConnected = true;

        return $this;
    }

    /**
     * Close Connection
     */
    public function close(): AbstractClient
    {
        if (null !== $this->http2ch) {
            curl_close($this->http2ch);
            $this->http2ch = null;
            $this->isConnected = false;
        }
        return $this;
    }

    /**
     * Is Connected
     */
    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    /**
     * Read from the Server
     */
    protected function read(int $length = 6): string
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to reading data');
        }
        return "";
    }

    /**
     * @param string $app_bundle_id the app bundle id
     * @param string $payload the payload to send (JSON)
     * @param string $token the token of the device
     * @return string|bool                   the status code (see https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/handling_notification_responses_from_apns?language=objc)
     */
    protected function write(string $app_bundle_id, string $payload, string $token): string|bool
    {
        $http2_server = $this->uris[$this->environment];

        // url (endpoint)
        $url = "$http2_server/3/device/$token";

        // certificate
        $cert = realpath($this->certificate);

        // headers
        $headers = [
            "apns-topic: $app_bundle_id",
            "User-Agent: My Sender"
        ];

        // other curl options
        curl_setopt_array($this->http2ch, [
            CURLOPT_URL => "$url",
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLCERT => $cert,
            CURLOPT_SSLCERTPASSWD => $this->passPhrase,
            CURLOPT_HEADER => 1
        ]);

        $result = curl_exec($this->http2ch);
        if ($result === false) {
            throw new Exception\RuntimeException('Curl failed with error: ' . curl_error($this->http2ch));
        }

        $this->responseStatus = curl_getinfo($this->http2ch, CURLINFO_HTTP_CODE);

        $parts = explode("\r\n\r\nHTTP/", $result);
        $parts = (count($parts) > 1 ? 'HTTP/' : '') . array_pop($parts);
        [$headers, $body] = explode("\r\n\r\n", $parts, 2);
        $this->responseBody = $body;

        $headerList = explode("\r\n", $headers);
        $this->responseId = "";
        foreach ($headerList as $i => $header) {
            if ($i > 0) {
                [$key, $value] = explode(': ', $header);
                if (strcmp('apns-id', $key) == 0) {
                    $this->responseId = $value;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
}
