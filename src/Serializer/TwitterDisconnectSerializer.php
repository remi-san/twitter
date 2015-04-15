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

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\TwitterDisconnect
     */
    public function unserialize($obj, array $context = array())
    {
        $d = $obj->disconnect;

        return new TwitterDisconnect(
            $d->code,
            $d->stream_name,
            $d->reason
        );
    }
} 