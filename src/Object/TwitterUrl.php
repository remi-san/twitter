<?php

namespace Twitter\Object;

use Twitter\TwitterEntity;

class TwitterUrl extends TwitterEntity
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $displayUrl;

    /**
     * @var string
     */
    private $expandedUrl;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getDisplayUrl()
    {
        return $this->displayUrl;
    }

    /**
     * @return string
     */
    public function getExpandedUrl()
    {
        return $this->expandedUrl;
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
     * @param string               $url
     * @param string               $displayUrl
     * @param string               $expandedUrl
     * @param TwitterEntityIndices $indices
     *
     * @return TwitterUrl
     */
    public static function create($url, $displayUrl, $expandedUrl, TwitterEntityIndices $indices)
    {
        $obj = new self();

        $obj->initTwitterEntity($indices);

        $obj->displayUrl = $displayUrl;
        $obj->expandedUrl = $expandedUrl;
        $obj->url = $url;

        return $obj;
    }
}
