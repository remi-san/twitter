<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterDisconnect;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterDisconnectSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterDisconnect)) {
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
    public function unserialize($obj, array $context = array())
    {
        $d = $obj->disconnect;

        return TwitterDisconnect::create(
            $d->code,
            $d->stream_name,
            $d->reason
        );
    }

    /**
     * @return TwitterDisconnectSerializer
     */
    public static function build()
    {
        return new self();
    }
}
