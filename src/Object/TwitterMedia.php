<?php

namespace Twitter\Object;

class TwitterMedia extends AbstractTwitterMedia
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Static constructor.
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
     *
     * @return TwitterMedia
     */
    public static function create(
        $id = null,
        $mediaUrl = null,
        $mediaUrlHttps = null,
        $url = null,
        $displayUrl = null,
        $expandedUrl = null,
        array $sizes = [],
        $type = null,
        TwitterEntityIndices $indices = null
    ) {
        $obj = new self();

        $obj->initTwitterMedia(
            $id,
            $mediaUrl,
            $mediaUrlHttps,
            $url,
            $displayUrl,
            $expandedUrl,
            $sizes,
            $type,
            $indices
        );

        return $obj;
    }
}
