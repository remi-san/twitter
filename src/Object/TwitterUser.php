<?php
namespace Twitter\Object;

use Twitter\TwitterBasicUser;
use Twitter\TwitterSerializable;

class TwitterUser implements TwitterBasicUser, TwitterSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $screenName;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $profileImageUrl;

    /**
     * @var string
     */
    private $profileImageUrlHttps;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getProfileImageUrl()
    {
        return $this->profileImageUrl;
    }

    /**
     * @return string
     */
    public function getProfileImageUrlHttps()
    {
        return $this->profileImageUrlHttps;
    }

    /**
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '@'.$this->screenName;
    }

    /**
     * Static constructor.
     *
     * @param int    $id
     * @param string $screenName
     * @param string $name
     * @param string $lang
     * @param string $location
     * @param string $profileImageUrl
     * @param string $profileImageUrlHttps
     *
     * @return TwitterUser
     */
    public static function create(
        $id = null,
        $screenName = null,
        $name = null,
        $lang = 'en',
        $location = null,
        $profileImageUrl = null,
        $profileImageUrlHttps = null
    ) {
        $obj = new self();

        $obj->id = $id;

        $obj->screenName = $screenName;
        $obj->name = $name;

        $obj->lang = $lang;
        $obj->location = $location;

        $obj->profileImageUrl = $profileImageUrl;
        $obj->profileImageUrlHttps = $profileImageUrlHttps;

        return $obj;
    }
}
