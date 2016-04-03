<?php

namespace Twitter;

interface TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass|array
     */
    public function serialize(TwitterSerializable $object);

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterSerializable
     */
    public function unserialize($obj, array $context = []);
}
