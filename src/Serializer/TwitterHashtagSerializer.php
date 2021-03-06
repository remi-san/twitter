<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterHashtag;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterHashtagSerializer implements TwitterSerializer
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
        /* @var TwitterHashtag $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterHashtag');

        $hashtag = new \stdClass();
        $hashtag->text = $object->getText();
        $hashtag->indices = $this->entityIndicesSerializer->serialize($object->getIndices());

        return $hashtag;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterHashtag
     */
    public function unserialize($obj, array $context = [])
    {
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        return TwitterHashtag::create($obj->text, $this->entityIndicesSerializer->unserialize($obj->indices));
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterHashtag;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->text) && isset($object->indices);
    }

    /**
     * @return TwitterHashtagSerializer
     */
    public static function build()
    {
        return new self(
            TwitterEntityIndicesSerializer::build()
        );
    }
}
