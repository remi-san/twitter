<?php

namespace Twitter;

interface Serializer
{
    /**
     * @param  TwitterSerializable $object
     * @return string
     */
    public function serialize(TwitterSerializable $object);

    /**
     * @param  string $string
     * @return TwitterSerializable
     */
    public function unserialize($string);
}
