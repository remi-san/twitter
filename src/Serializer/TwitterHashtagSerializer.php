<?php
namespace Twitter\Serializer;


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
     * @param  \Twitter\TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterHashtag)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterHashtag');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\TwitterHashtag
     */
    public function unserialize($obj, array $context = array())
    {
        return new TwitterHashtag($obj->text, $this->entityIndicesSerializer->unserialize($obj->indices));
    }
}