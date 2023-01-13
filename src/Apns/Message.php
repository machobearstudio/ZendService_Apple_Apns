<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendService\Apple\Apns;

use DateTime;
use Laminas\Json\Encoder;
use ZendService\Apple\Exception;

/**
 * APNs Message
 */
class Message
{
    /**
     * Identifier
     * @var string
     */
    protected string $id;

    /**
     * App Bundle Id
     * @var string
     */
    protected string $bundleId;

    /**
     * APN Token
     * @var string
     */
    protected string $token;

    /**
     * Expiration
     * @var int|null
     */
    protected ?int $expire = null;

    /**
     * Alert Message
     * @var Message\Alert|null
     */
    protected ?Message\Alert $alert = null;

    /**
     * Badge
     * @var int|null
     */
    protected ?int $badge = null;

    /**
     * Sound
     * @var string|null
     */
    protected ?string $sound = null;

    /**
     * Mutable Content
     * @var int|null
     */
    private ?int $mutableContent = null;

    /**
     * Content Available
     * @var int|null
     */
    protected ?int $contentAvailable = null;

    /**
     * Category
     * @var string|null
     */
    protected ?string $category = null;

    /**
     * URL Arguments
     * @var array|null
     */
    protected ?array $urlArgs = null;

    /**
     * Custom Attributes
     * @var array|null
     */
    protected ?array $custom = null;

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set Identifier
     *
     * @param string $id
     * @return Message
     */
    public function setId(string $id): Message
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get App Bundle Id
     *
     * @return string
     */
    public function getBundleId(): string
    {
        return $this->bundleId;
    }

    /**
     * Set App Bundle Id
     *
     * @param string $bundleId
     * @return Message
     */
    public function setBundleId(string $bundleId): Message
    {
        if (strlen($bundleId) == 0) {
            throw new Exception\InvalidArgumentException(
                'App Bundle Id must not be an empty string'
            );
        }
        $this->bundleId = $bundleId;
        return $this;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set Token
     *
     * @param string $token
     * @return Message
     */
    public function setToken(string $token): Message
    {
        if (preg_match('/[^0-9a-f]/i', $token)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Device token must be mask "%s". Token given: "%s"',
                '/[^0-9a-f]/',
                $token
            ));
        }

        if (strlen($token) != 64) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Device token must be a 64 charsets, Token length given: %d.',
                mb_strlen($token)
            ));
        }

        $this->token = $token;

        return $this;
    }

    /**
     * Get Expiration
     *
     * @return int|null
     */
    public function getExpire(): ?int
    {
        return $this->expire;
    }

    /**
     * Set Expiration
     *
     * @param int|DateTime $expire
     * @return Message
     */
    public function setExpire(int|DateTime $expire): Message
    {
        if ($expire instanceof DateTime) {
            $expire = $expire->getTimestamp();
        } elseif (!is_numeric($expire) || $expire != (int)$expire) {
            throw new Exception\InvalidArgumentException('Expiration must be a DateTime object or a unix timestamp');
        }
        $this->expire = $expire;

        return $this;
    }

    /**
     * Get Alert
     *
     * @return Message\Alert|null
     */
    public function getAlert(): ?Message\Alert
    {
        return $this->alert;
    }

    /**
     * Set Alert
     *
     * @param string|Message\Alert|null $alert
     * @return Message
     */
    public function setAlert(Message\Alert|string|null $alert): Message
    {
        if (!$alert instanceof Message\Alert && !is_null($alert)) {
            $alert = new Message\Alert($alert);
        }
        $this->alert = $alert;

        return $this;
    }

    /**
     * Get Badge
     *
     * @return int|null
     */
    public function getBadge(): ?int
    {
        return $this->badge;
    }

    /**
     * Set Badge
     *
     * @param int|null $badge
     * @return Message
     */
    public function setBadge(?int $badge): Message
    {
        if ($badge !== null) {
            throw new Exception\InvalidArgumentException('Badge must be null');
        }
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get Sound
     *
     * @return string|null
     */
    public function getSound(): ?string
    {
        return $this->sound;
    }

    /**
     * Set Sound
     *
     * @param string|null $sound
     * @return Message
     */
    public function setSound(?string $sound): Message
    {
        if ($sound !== null && empty($sound)) {
            throw new Exception\InvalidArgumentException('Sound must be null or a non empty string');
        }
        $this->sound = $sound;

        return $this;
    }

    /**
     * Set Mutable Content
     *
     * @param int|null $value
     * @return Message
     */
    public function setMutableContent(?int $value): Message
    {
        if ($value !== null && !is_int($value)) {
            throw new Exception\InvalidArgumentException(
                'Mutable Content must be null or an integer, received: ' . gettype($value)
            );
        }

        if (is_int($value) && $value !== 1) {
            throw new Exception\InvalidArgumentException('Mutable Content supports only 1 as integer value');
        }

        $this->mutableContent = $value;

        return $this;
    }

    /**
     * Get Content Available
     *
     * @return int|null
     */
    public function getContentAvailable(): ?int
    {
        return $this->contentAvailable;
    }

    /**
     * Set Content Available
     *
     * @param int|null $value
     * @return Message
     */
    public function setContentAvailable(?int $value): Message
    {
        if ($value !== null && !is_int($value)) {
            throw new Exception\InvalidArgumentException('Content Available must be null or an integer');
        }
        $this->contentAvailable = $value;

        return $this;
    }

    /**
     * Get Category
     *
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * Set Category
     *
     * @param string|null $category
     * @return Message
     */
    public function setCategory(?string $category): Message
    {
        if ($category == null || !is_string($category)) {
            throw new Exception\InvalidArgumentException('Category must be null or a string');
        }
        $this->category = $category;

        return $this;
    }

    /**
     * Get URL arguments
     *
     * @return array|null
     */
    public function getUrlArgs(): ?array
    {
        return $this->urlArgs;
    }

    /**
     * Set URL arguments
     *
     * @param array $urlArgs
     * @return Message
     */
    public function setUrlArgs(array $urlArgs): Message
    {
        $this->urlArgs = $urlArgs;

        return $this;
    }

    /**
     * Get Custom Data
     *
     * @return array|null
     */
    public function getCustom(): ?array
    {
        return $this->custom;
    }

    /**
     * Set Custom Data
     *
     * @param array $custom
     * @return Message
     */
    public function setCustom(array $custom): Message
    {
        if (array_key_exists('aps', $custom)) {
            throw new Exception\RuntimeException('custom data must not contain aps key as it is reserved by apple');
        }

        $this->custom = $custom;

        return $this;
    }

    /**
     * Get Payload
     * Generate APN array.
     *
     * @return array
     */
    public function getPayload(): array
    {
        $message = [];
        $aps = [];
        if ($this->alert && ($alert = $this->alert->getPayload())) {
            $aps['alert'] = $alert;
        }
        if (!is_null($this->badge)) {
            $aps['badge'] = $this->badge;
        }
        if (!is_null($this->sound)) {
            $aps['sound'] = $this->sound;
        }
        if (!is_null($this->mutableContent)) {
            $aps['mutable-content'] = $this->mutableContent;
        }
        if (!is_null($this->contentAvailable)) {
            $aps['content-available'] = $this->contentAvailable;
        }
        if (!is_null($this->category)) {
            $aps['category'] = $this->category;
        }
        if (!is_null($this->urlArgs)) {
            $aps['url-args'] = $this->urlArgs;
        }
        if (!empty($this->custom)) {
            $message = array_merge($this->custom, $message);
        }

        $message['aps'] = empty($aps) ? (object)[] : $aps;

        return $message;
    }

    /**
     * Get Payload JSON
     *
     * @return string
     */
    public function getPayloadJson(): string
    {
        $payload = $this->getPayload();
        // don't escape utf8 payloads unless json_encode does not exist.
        if (defined('JSON_UNESCAPED_UNICODE')) {
            $payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
        } else {
            $payload = Encoder::encode($payload);
        }
        return $payload;
        /*$length = strlen($payload);
        return pack('CNNnH*', 1, $this->id, $this->expire, 32, $this->token)
            . pack('n', $length)
            . $payload;*/
    }
}
