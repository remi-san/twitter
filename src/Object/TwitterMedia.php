<?php
namespace Twitter\Object;

use Twitter\TwitterEntity;

class TwitterMedia extends TwitterEntity
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $mediaUrl;

    /**
     * @var string
     */
    private $mediaUrlHttps;

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
     * @var TwitterMediaSize[]
     */
    private $sizes;

    /**
     * @var string
     */
    private $type;

    /**
     * Constructor
     *
     * @param int                  $id
     * @param string               $mediaUrl
     * @param string               $mediaUrlHttps
     * @param string               $url
     * @param string               $displayUrl
     * @param string               $expandedUrl
     * @param TwitterMediaSize[]   $sizes
     * @param string               $type
     * @param TwitterEntityIndices $indices
     */
    function __construct($id = null, $mediaUrl = null, $mediaUrlHttps = null, $url = null, $displayUrl = null, $expandedUrl = null, array $sizes = array(), $type = null, TwitterEntityIndices $indices = null)
    {
        parent::__construct($indices);
        $this->displayUrl = $displayUrl;
        $this->expandedUrl = $expandedUrl;
        $this->id = $id;
        $this->mediaUrl = $mediaUrl;
        $this->mediaUrlHttps = $mediaUrlHttps;
        $this->sizes = $sizes;
        $this->type = $type;
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->mediaUrl;
    }

    /**
     * @return string
     */
    public function getMediaUrlHttps()
    {
        return $this->mediaUrlHttps;
    }

    /**
     * @return TwitterMediaSize[]
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
} 