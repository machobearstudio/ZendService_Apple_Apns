<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendService\Apple\Apns\Response;

use ZendService\Apple\Exception;

/**
 * Feedback Response
 */
class Feedback
{
    /**
     * APNS Token
     * @var string
     */
    protected string $token;

    /**
     * Time
     * @var int
     */
    protected int $time;

    /**
     * Constructor
     *
     * @param string|null $rawResponse
     * @return Feedback
     */
    public function __construct(string $rawResponse = null)
    {
        if ($rawResponse !== null) {
            $this->parseRawResponse($rawResponse);
        }
        return $this;
    }

    /**
     * Get Token
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set Token
     *
     * @param $token
     * @return Feedback
     */
    public function setToken($token): Feedback
    {
        if (!is_scalar($token)) {
            throw new Exception\InvalidArgumentException('Token must be a scalar value');
        }
        $this->token = $token;

        return $this;
    }

    /**
     * Get Time
     *
     * @return int
     * @noinspection PhpUnused
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * Set Time
     *
     * @param int $time
     * @return Feedback
     */
    public function setTime(int $time): Feedback
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Parse Raw Response
     *
     * @param $rawResponse
     * @return Feedback
     */
    public function parseRawResponse($rawResponse): Feedback
    {
        $rawResponse = trim((string)$rawResponse);
        $token = unpack('Ntime/nlength/H*token', $rawResponse);
        $this->setTime($token['time']);
        $this->setToken(substr((string)$token['token'], 0, $token['length'] * 2));

        return $this;
    }
}
