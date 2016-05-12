<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterUrl;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterUrlSerializer implements TwitterSerializer
{
    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    /**
     * Constructor
     *
     * @param TwitterEntityIndicesSerializer $entityIndicesSerializer
     */
    public function __construct(TwitterEntityIndicesSerializer $entityIndicesSerializer)
    {
        $this->entityIndicesSerializer  = $entityIndicesSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterUrl');
        }

        /* @var TwitterUrl $object */
        $url = new \stdClass();
        $url->url = $object->getUrl();
        $url->display_url = $object->getDisplayUrl();
        $url->expanded_url = $object->getExpandedUrl();
        $url->indices = $this->entityIndicesSerializer->serialize($object->getIndices());

        return $url;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterUrl
     */
    public function unserialize($obj, array $context = [])
    {
        if (!$this->canUnserialize($obj)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        return TwitterUrl::create(
            $obj->url,
            $obj->display_url,
            $obj->expanded_url,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterUrl;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->url) && isset($object->indices);
    }

    /**
     * @return TwitterUrlSerializer
     */
    public static function build()
    {
        return new self(
            TwitterEntityIndicesSerializer::build()
        );
    }
}
