<?php

namespace Twitter\Serializer;

use Assert\Assertion;
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
    public function __construct(
        TwitterEntityIndicesSerializer $entityIndicesSerializer,
        TwitterMediaSizeSerializer $mediaSizeSerializer
    ) {
        $this->entityIndicesSerializer  = $entityIndicesSerializer;
        $this->mediaSizeSerializer = $mediaSizeSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterMedia $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterMedia');

        $media = new \stdClass();
        $media->id = $object->getId();
        $media->media_url = $object->getMediaUrl();
        $media->media_url_https = $object->getMediaUrlHttps();
        $media->url = $object->getUrl();
        $media->display_url = $object->getDisplayUrl();
        $media->expanded_url = $object->getExpandedUrl();
        $media->type = $object->getType();
        $media->indices = $this->entityIndicesSerializer->serialize($object->getIndices());

        $media->sizes = [];
        foreach ($object->getSizes() as $size) {
            $media->sizes[$size->getName()] = $this->mediaSizeSerializer->serialize($size);
        }

        return $media;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterMedia
     */
    public function unserialize($obj, array $context = [])
    {
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        $sizesObjects = [];
        if ($obj->sizes) {
            foreach ($obj->sizes as $sizeName => $sizeObj) {
                $sizesObjects[$sizeName] = $this->mediaSizeSerializer->unserialize(
                    $sizeObj,
                    [TwitterMediaSizeSerializer::NAME_VAR => $sizeName]
                );
            }
        }

        return TwitterMedia::create(
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

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterMedia;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->media_url) && !isset($object->duration_millis);
    }

    /**
     * @return TwitterMediaSerializer
     */
    public static function build()
    {
        return new self(
            TwitterEntityIndicesSerializer::build(),
            TwitterMediaSizeSerializer::build()
        );
    }
}
