<?php
namespace Twitter;

interface Serializer {

    /**
     * @param  object $object
     * @return string
     */
    public function serialize($object);

    /**
     * @param  string $string
     * @return object
     */
    public function unserialize($string);
} 