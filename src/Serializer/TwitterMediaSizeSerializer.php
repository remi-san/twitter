<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterMediaSize;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterMediaSizeSerializer implements TwitterSerializer
{
    const NAME_VAR = 'sizeName';

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterMediaSize $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterMediaSize');

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
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        return TwitterMediaSize::create($context[self::NAME_VAR], $obj->w, $obj->h, $obj->resize);
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterMediaSize;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->w) &&  isset($object->h);
    }

    /**
     * @return TwitterMediaSizeSerializer
     */
    public static function build()
    {
        return new self();
    }
}
