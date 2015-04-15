<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterEntityIndices;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterEntityIndicesSerializer implements TwitterSerializer
{

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterEntityIndices)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEntityIndices');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  array $array
     * @param  array $context
     * @return \Twitter\Object\TwitterEntityIndices
     */
    public function unserialize($array, array $context = array())
    {
        return new TwitterEntityIndices($array[0], $array[1]);
    }
}