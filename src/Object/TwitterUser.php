<?php
namespace Twitter\Object;

use Twitter\TwitterSerializable;

class TwitterUser implements TwitterSerializable
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
     * Constructor
     *
     * @param int    $id
     * @param string $screenName
     * @param string $name
     * @param string $lang
     * @param string $location
     * @param string $profileImageUrl
     * @param string $profileImageUrlHttps
     */
    function __construct($id = null, $screenName = null, $name = null, $lang = 'en', $location = null, $profileImageUrl = null, $profileImageUrlHttps = null)
    {
        $this->id = $id;

        $this->screenName = $screenName;
        $this->name = $name;

        $this->lang = $lang;
        $this->location = $location;

        $this->profileImageUrl = $profileImageUrl;
        $this->profileImageUrlHttps = $profileImageUrlHttps;

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
} 