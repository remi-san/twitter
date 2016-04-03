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
     * Constructor.
     */
    public function __construct()
    {
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

    /**
     * Static constructor.
     *
     * @param string $contentType
     * @param string $url
     * @param int    $bitrate
     *
     * @return TwitterVariantMedia
     */
    public static function create($contentType, $url, $bitrate)
    {
        $obj = new self();

        $obj->bitrate = $bitrate;
        $obj->contentType = $contentType;
        $obj->url = $url;

        return $obj;
    }
}
