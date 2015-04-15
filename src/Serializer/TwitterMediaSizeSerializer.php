<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterMediaSize;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterMediaSizeSerializer implements TwitterSerializer
{
    const NAME_VAR = 'sizeName';

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterMediaSize)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterMediaSize');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\TwitterMediaSize
     */
    public function unserialize($obj, array $context = array())
    {
        return new TwitterMediaSize($context[self::NAME_VAR], $obj->w, $obj->h, $obj->resize);
    }
}