<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterVariantMedia;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterVariantMediaSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterVariantMedia)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterVariantMedia');
        }

        $variantMedia = new \stdClass();
        $variantMedia->content_type = $object->getContentType();
        $variantMedia->url = $object->getUrl();
        $variantMedia->bitrate = $object->getBitrate();

        return $variantMedia;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterVariantMedia
     */
    public function unserialize($obj, array $context = [])
    {
        return TwitterVariantMedia::create($obj->content_type, $obj->url, $obj->bitrate?:null);
    }

    /**
     * @return TwitterVariantMediaSerializer
     */
    public static function build()
    {
        return new self();
    }
}
