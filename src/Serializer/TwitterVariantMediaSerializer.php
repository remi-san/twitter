<?php

namespace Twitter\Serializer;

use Assert\Assertion;
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
        /* @var TwitterVariantMedia $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterVariantMedia');

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
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        return TwitterVariantMedia::create($obj->content_type, $obj->url, $obj->bitrate?:null);
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterVariantMedia;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->content_type) && isset($object->url) && isset($object->bitrate);
    }

    /**
     * @return TwitterVariantMediaSerializer
     */
    public static function build()
    {
        return new self();
    }
}
