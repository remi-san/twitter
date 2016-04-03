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

        $size = new \stdClass();
        $size->w = $object->getWidth();
        $size->h = $object->getHeight();
        $size->resize = $object->getResize();

        return $size;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterMediaSize
     */
    public function unserialize($obj, array $context = [])
    {
        return TwitterMediaSize::create($context[self::NAME_VAR], $obj->w, $obj->h, $obj->resize);
    }

    /**
     * @return TwitterMediaSizeSerializer
     */
    public static function build()
    {
        return new self();
    }
}
