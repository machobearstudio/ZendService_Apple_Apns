<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendServiceTest\Apple\Apns\TestAsset;

use ZendService\Apple\Exception;
use ZendService\Apple\Apns\Client\Message as ZfMessageClient;

/**
 * Message Client Proxy
 * This class is utilized for unit testing purposes
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class MessageClient extends ZfMessageClient
{
    /**
     * Read Response
     *
     * @var string
     */
    protected string $readResponse;

    /**
     * Write Response
     *
     * @var string|null
     */
    protected ?string $writeResponse = null;

    /**
     * Set the Response
     *
     * @param string $str
     * @return MessageClient
     */
    public function setReadResponse(string $str): MessageClient
    {
        $this->readResponse = $str;

        return $this;
    }

    /**
     * Set the write response
     *
     * @param mixed $resp
     * @return MessageClient
     * @noinspection PhpUnused
     */
    public function setWriteResponse(mixed $resp): MessageClient
    {
        $this->writeResponse = $resp;

        return $this;
    }

    /**
     * Connect to Host
     *
     * @param $host
     * @param array $ssl
     * @return MessageClient
     * @noinspection PhpUnusedParameterInspection
     */
    protected function connect($host, array $ssl): MessageClient
    {
        return $this;
    }

    /**
     * Return Response
     *
     * @param int $length
     * @return string
     */
    protected function read(int $length = 1024): string
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to reading data');
        }
        $ret = substr($this->readResponse, 0, $length);
        $this->readResponse = null;

        return $ret;
    }

    /**
     * @param string $app_bundle_id the app bundle id
     * @param string $payload the payload to send (JSON)
     * @param string $token the token of the device
     * @return bool|string the status code
     */
    protected function write(string $app_bundle_id, string $payload, string $token): bool|string
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to writing data');
        }
        $ret = $this->writeResponse;
        $this->writeResponse = null;

        return $ret ?? strlen($payload);
    }
}
