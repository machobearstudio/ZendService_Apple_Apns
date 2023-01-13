<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendService\Apple\Apns\Message;

use ZendService\Apple\Exception;

/**
 * Message Alert Object
 */
class Alert
{
    /**
     * Message Body
     * @var string|null
     */
    protected ?string $body = null;

    /**
     * Action
     * @var string|null
     */
    protected ?string $action = null;

    /**
     * Action Locale Key
     * @var string|null
     */
    protected ?string $actionLocKey = null;

    /**
     * Locale Key
     * @var string|null
     */
    protected ?string $locKey = null;

    /**
     * Locale Arguments
     * @var array|null
     */
    protected ?array $locArgs;

    /**
     * Launch Image
     * @var string|null
     */
    protected ?string $launchImage = null;

    /**
     * Message Title
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * Title Locale Key
     * @var string|null
     */
    protected ?string $titleLocKey = null;

    /**
     * Title Locale Arguments
     * @var array|null
     */
    protected ?array $titleLocArgs;

    /**
     * Constructor
     *
     * @param string|null $body
     * @param string|null $actionLocKey
     * @param string|null $locKey
     * @param array|null $locArgs
     * @param string|null $launchImage
     * @return Alert
     */
    public function __construct(
        string $body = null,
        string $actionLocKey = null,
        string $locKey = null,
        array  $locArgs = null,
        string $launchImage = null,
               $title = null,
               $titleLocKey = null,
               $titleLocArgs = null
    )
    {
        if ($body !== null) {
            $this->setBody($body);
        }
        if ($actionLocKey !== null) {
            $this->setActionLocKey($actionLocKey);
        }
        if ($locKey !== null) {
            $this->setLocKey($locKey);
        }
        if ($locArgs !== null) {
            $this->setLocArgs($locArgs);
        }
        if ($launchImage !== null) {
            $this->setLaunchImage($launchImage);
        }
        if ($title !== null) {
            $this->setTitle($title);
        }
        if ($titleLocKey !== null) {
            $this->setTitleLocKey($titleLocKey);
        }
        if ($titleLocArgs !== null) {
            $this->setTitleLocArgs($titleLocArgs);
        }
        return $this;
    }

    /**
     * Get Body
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Set Body
     */
    public function setBody(?string $body): Alert
    {
        if (!is_null($body) && !is_scalar($body)) {
            throw new Exception\InvalidArgumentException('Body must be null OR a scalar value');
        }
        $this->body = $body;

        return $this;
    }

    /**
     * Get Action
     * @noinspection PhpUnused
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Set Action
     *
     * @param string|null $key
     * @return Alert
     * @noinspection PhpUnused
     */
    public function setAction(?string $key): Alert
    {
        if (!is_null($key) && !is_scalar($key)) {
            throw new Exception\InvalidArgumentException('Action must be null OR a scalar value');
        }
        $this->action = $key;

        return $this;
    }

    /**
     * Get Action Locale Key
     */
    public function getActionLocKey(): ?string
    {
        return $this->actionLocKey;
    }

    /**
     * Set Action Locale Key
     *
     * @param string|null $key
     * @return Alert
     */
    public function setActionLocKey(?string $key): Alert
    {
        if (!is_null($key) && empty($key)) {
            throw new Exception\InvalidArgumentException('ActionLocKey must be null OR a non empty string value');
        }
        $this->actionLocKey = $key;

        return $this;
    }

    /**
     * Get Locale Key
     *
     * @return string|null
     */
    public function getLocKey(): ?string
    {
        return $this->locKey;
    }

    /**
     * Set Locale Key
     *
     * @param string|null $key
     * @return Alert
     */
    public function setLocKey(?string $key): Alert
    {
        if (!is_null($key) && empty($key)) {
            throw new Exception\InvalidArgumentException('LocKey must be null OR a non empty string value');
        }
        $this->locKey = $key;

        return $this;
    }

    /**
     * Get Locale Arguments
     *
     * @return array|null
     */
    public function getLocArgs(): ?array
    {
        return $this->locArgs;
    }

    /**
     * Set Locale Arguments
     *
     * @param array $args
     * @return Alert
     */
    public function setLocArgs(array $args): Alert
    {
        foreach ($args as $a) {
            if (!is_scalar($a)) {
                throw new Exception\InvalidArgumentException('Arguments must only contain scalar values');
            }
        }
        $this->locArgs = $args;

        return $this;
    }

    /**
     * Get Launch Image
     *
     * @return string|null
     */
    public function getLaunchImage(): ?string
    {
        return $this->launchImage;
    }

    /**
     * Set Launch Image
     *
     * @param string|null $image
     * @return Alert
     */
    public function setLaunchImage(?string $image): Alert
    {
        if (!is_null($image) && !is_scalar($image)) {
            throw new Exception\InvalidArgumentException('Launch image must be null OR a scalar value');
        }
        $this->launchImage = $image;

        return $this;
    }

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set Title
     *
     * @param string|null $title
     * @return Alert
     */
    public function setTitle(?string $title): Alert
    {
        if (!is_null($title) && !is_scalar($title)) {
            throw new Exception\InvalidArgumentException('Title must be null OR a scalar value');
        }
        $this->title = $title;

        return $this;
    }

    /**
     * Get Title Locale Key
     *
     * @return string|null
     */
    public function getTitleLocKey(): ?string
    {
        return $this->titleLocKey;
    }

    /**
     * Set Title Locale Key
     *
     * @param string|null $key
     * @return Alert
     */
    public function setTitleLocKey(?string $key): Alert
    {
        if (!is_null($key) || !is_scalar($key)) {
            throw new Exception\InvalidArgumentException('TitleLocKey must be null OR a scalar value');
        }
        $this->titleLocKey = $key;

        return $this;
    }

    /**
     * Get Title Locale Arguments
     *
     * @return array|null
     */
    public function getTitleLocArgs(): ?array
    {
        return $this->titleLocArgs;
    }

    /**
     * Set Title Locale Arguments
     *
     * @param array $args
     * @return Alert
     */
    public function setTitleLocArgs(array $args): Alert
    {
        foreach ($args as $a) {
            if (!is_scalar($a)) {
                throw new Exception\InvalidArgumentException('Title Arguments must only contain scalar values');
            }
        }
        $this->titleLocArgs = $args;

        return $this;
    }

    /**
     * To Payload
     * Formats an APS alert.
     */
    public function getPayload(): null|array|string
    {
        $vars = get_object_vars($this);
        if (empty($vars)) {
            return null;
        }

        $alert = [];
        foreach ($vars as $key => $value) {
            if (!is_null($value)) {
                $key = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $key));
                $alert[$key] = $value;
            }
        }

        if (count($alert) === 1) {
            return $this->getBody();
        }

        return $alert;
    }
}
