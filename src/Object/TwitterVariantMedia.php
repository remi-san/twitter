<?php
namespace Twitter\Object;


use Twitter\TwitterSerializable;

class TwitterVariantMedia implements TwitterSerializable
{

    /**
     * @var string
     */
    private $contentType;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $bitrate;

    /**
     * Constructor
     *
     * @param string $contentType
     * @param string $url
     * @param int    $bitrate
     */
    function __construct($contentType, $url, $bitrate)
    {
        $this->bitrate = $bitrate;
        $this->contentType = $contentType;
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
} 