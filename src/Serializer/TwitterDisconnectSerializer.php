<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterDisconnect;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterDisconnectSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterDisconnect');
        }

        $obj = new \stdClass();
        $obj->code = $object->getCode();
        $obj->stream_name = $object->getStreamName();
        $obj->reason = $object->getReason();

        $disconnect = new \stdClass();
        $disconnect->disconnect = $obj;

        return $disconnect;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterDisconnect
     */
    public function unserialize($obj, array $context = [])
    {
        if (!$this->canUnserialize($obj)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        $d = $obj->disconnect;

        return TwitterDisconnect::create(
            $d->code,
            $d->stream_name,
            $d->reason
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterDisconnect;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return (isset($object->disconnect));
    }

    /**
     * @return TwitterDisconnectSerializer
     */
    public static function build()
    {
        return new self();
    }
}
