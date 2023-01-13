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
use ZendService\Apple\Apns\Client\Feedback as ZfFeedbackClient;

/**
 * Feedback Client Proxy
 * This class is utilized for unit testing purposes
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class FeedbackClient extends ZfFeedbackClient
{
    /**
     * Read Response
     *
     * @var string|null
     */
    protected ?string $readResponse;

    /**
     * Write Response
     *
     * @var mixed
     */
    protected mixed $writeResponse;

    /**
     * Set the Response
     *
     * @param string $str
     * @return FeedbackClient
     */
    public function setReadResponse(string $str): FeedbackClient
    {
        $this->readResponse = $str;

        return $this;
    }

    /**
     * Set the write response
     *
     * @param mixed $resp
     * @return FeedbackClient
     * @noinspection PhpUnused
     */
    public function setWriteResponse(mixed $resp): FeedbackClient
    {
        $this->writeResponse = $resp;

        return $this;
    }

    /**
     * Connect to Host
     *
     * @param $host
     * @param array $ssl
     * @return FeedbackClient
     * @noinspection PhpUnusedParameterInspection
     */
    protected function connect($host, array $ssl): FeedbackClient
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
        if ($this->readResponse != null) {
            $ret = substr($this->readResponse, 0, $length);
        } else {
            $ret = "";
        }
        $this->readResponse = null;

        return $ret;
    }

    /**
     * Write and Return Length
     *
     * @param string $app_bundle_id
     * @param string $payload
     * @param string $token
     * @return bool|string
     */
    protected function write(string $app_bundle_id, string $payload, string $token): bool|string
    {
        if (!$this->isConnected()) {
            throw new Exception\RuntimeException('You must open the connection prior to writing data');
        }
        $ret = $this->writeResponse;
        $this->writeResponse = null;

        return $ret ?? strlen($app_bundle_id);
    }
}
