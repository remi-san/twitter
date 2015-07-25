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
     * Constructor
     *
     * @param string               $url
     * @param string               $displayUrl
     * @param string               $expandedUrl
     * @param TwitterEntityIndices $indices
     */
    public function __construct($url, $displayUrl, $expandedUrl, TwitterEntityIndices $indices)
    {
        parent::__construct($indices);
        $this->displayUrl = $displayUrl;
        $this->expandedUrl = $expandedUrl;
        $this->url = $url;
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
}
