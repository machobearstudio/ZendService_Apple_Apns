<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link       http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright  Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */

namespace ZendService\Apple\Apns\Response;

use ZendService\Apple\Exception;

/**
 * Message Response
 *
 * @category   ZendService
 * @package    ZendService_Apple
 * @subpackage Apns
 */
class Message
{
    /**
     * Response Codes (see https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/handling_notification_responses_from_apns?language=objc)
     * @var int
     */
    final const RESULT_OK = 200;
    final const RESULT_BAD_REQUEST = 400;
    final const RESULT_BAD_AUTH = 403;
    final const RESULT_INVALID_PATH = 404;
    final const RESULT_INVALID_METHOD = 405;
    final const RESULT_INVALID_TOKEN = 410;
    final const RESULT_INVALID_PAYLOAD_SIZE = 413;
    final const RESULT_TOO_MANY_REQUESTS = 429;
    final const RESULT_INTERNAL_SERVER_ERROR = 500;
    final const RESULT_SERVER_UNAVAILABLE = 503;

    // old consts
    const RESULT_MISSING_TOKEN = 2;
    const RESULT_MISSING_TOPIC = 3;
    const RESULT_MISSING_PAYLOAD = 4;
    const RESULT_INVALID_TOKEN_SIZE = 5;
    const RESULT_INVALID_TOPIC_SIZE = 6;
    const RESULT_UNKNOWN_ERROR = 255;

    /**
     * Identifier
     * @var null|string
     */
    protected ?string $id;

    /**
     * Result Code
     * @var int
     */
    protected int $code;

    /**
     * Result JSON body
     * @var string
     */
    protected string $body;

    /**
     * Constructor
     *
     * @param string|null $rawResponse
     * @return Message
     */
    public function __construct(string $rawResponse = null)
    {
        if ($rawResponse !== null) {
            $this->parseRawResponse($rawResponse);
        }
        return $this;
    }

    /**
     * Get Code
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Set Code
     *
     * @param int $code
     * @return Message
     */
    public function setCode(int $code): Message
    {
        if ($code < 200 || $code > 503) {
            throw new Exception\InvalidArgumentException('Code must be between 200 and 503');
        }
        $this->code = $code;

        return $this;
    }

    /**
     * Get Identifier
     *
     * @return ?string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set Identifier
     *
     * @param ?string $id
     * @return Message
     */
    public function setId(?string $id): Message
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Return Response Body
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * set Response JSON Body
     * @param string $body
     * @return Message
     */
    public function setBody(string $body): Message
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Parse Raw Response
     *
     * @param string $rawResponse
     * @return Message
     * @noinspection PhpConditionAlreadyCheckedInspection
     */
    public function parseRawResponse(string $rawResponse): Message
    {
        if (!is_scalar($rawResponse)) {
            throw new Exception\InvalidArgumentException('Response must be a scalar value');
        }

        if (strlen($rawResponse) === 0) {
            $this->code = self::RESULT_OK;

            return $this;
        }
        $response = unpack('Ccmd/Cerrno/Nid', $rawResponse);
        $this->setId($response['id']);
        $this->setCode($response['errno']);

        return $this;
    }
}
