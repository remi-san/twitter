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
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterHashtag)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterHashtag');
        }

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
    public function unserialize($obj, array $context = array())
    {
        return new TwitterHashtag($obj->text, $this->entityIndicesSerializer->unserialize($obj->indices));
    }
}
