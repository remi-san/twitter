<?php
namespace Twitter\Serializer;


use Twitter\Object\TwitterVariantMedia;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterVariantMediaSerializer implements TwitterSerializer
{

    /**
     * @param  \Twitter\TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterVariantMedia)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterVariantMedia');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterVariantMedia
     */
    public function unserialize($obj, array $context = array())
    {
        return new TwitterVariantMedia($obj->content_type, $obj->url, $obj->bitrate?:null);
    }
}