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
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterUrl)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterUrl');
        }

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
    public function unserialize($obj, array $context = array())
    {
        return new TwitterUrl(
            $obj->url,
            $obj->display_url,
            $obj->expanded_url,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }
}