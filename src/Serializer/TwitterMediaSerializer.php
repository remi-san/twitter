<?php
namespace Twitter\Serializer;


use Twitter\Object\TwitterMedia;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterMediaSerializer implements TwitterSerializer
{
    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    /**
     * @var TwitterMediaSizeSerializer
     */
    private $mediaSizeSerializer;

    /**
     * Constructor
     *
     * @param TwitterEntityIndicesSerializer $entityIndicesSerializer
     * @param TwitterMediaSizeSerializer     $mediaSizeSerializer
     */
    public function __construct(TwitterEntityIndicesSerializer $entityIndicesSerializer, TwitterMediaSizeSerializer $mediaSizeSerializer)
    {
        $this->entityIndicesSerializer  = $entityIndicesSerializer;
        $this->mediaSizeSerializer = $mediaSizeSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterMedia)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterMedia');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterMedia
     */
    public function unserialize($obj, array $context = array())
    {
        $sizesObjects = array();
        if ($obj->sizes) {
            foreach ($obj->sizes as $sizeName => $sizeObj) {
                $sizesObjects[$sizeName] = $this->mediaSizeSerializer->unserialize($sizeObj, array(TwitterMediaSizeSerializer::NAME_VAR => $sizeName));
            }
        }

        return new TwitterMedia(
            $obj->id,
            $obj->media_url,
            $obj->media_url_https,
            $obj->url,
            $obj->display_url,
            $obj->expanded_url,
            $sizesObjects,
            $obj->type,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }
}