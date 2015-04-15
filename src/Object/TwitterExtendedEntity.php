<?php
namespace Twitter\Object;

class TwitterExtendedEntity extends TwitterMedia
{

    /**
     * @var string
     */
    private $videoInfo;

    /**
     * @var int
     */
    private $durationMillis;

    /**
     * @var TwitterVariantMedia[]
     */
    private $variants;

    /**
     * Constructor
     *
     * @param int                   $id
     * @param string                $mediaUrl
     * @param string                $mediaUrlHttps
     * @param string                $url
     * @param string                $displayUrl
     * @param string                $expandedUrl
     * @param TwitterMediaSize[]    $sizes
     * @param string                $type
     * @param string                $videoInfo
     * @param int                   $durationMillis
     * @param TwitterVariantMedia[] $variants
     * @param TwitterEntityIndices  $indices
     */
    function __construct($id = null, $mediaUrl = null, $mediaUrlHttps = null, $url = null, $displayUrl = null, $expandedUrl = null, array $sizes = array(), $type = null, $videoInfo = null, $durationMillis = null, array $variants = array(), TwitterEntityIndices $indices = null)
    {
        parent::__construct($id, $mediaUrl, $mediaUrlHttps, $url, $displayUrl, $expandedUrl, $sizes, $type, $indices);
        $this->durationMillis = $durationMillis;
        $this->variants = $variants;
        $this->videoInfo = $videoInfo;
    }


    /**
     * @return int
     */
    public function getDurationMillis()
    {
        return $this->durationMillis;
    }

    /**
     * @return TwitterVariantMedia[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @return string
     */
    public function getVideoInfo()
    {
        return $this->videoInfo;
    }
} 