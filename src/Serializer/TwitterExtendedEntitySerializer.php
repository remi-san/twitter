<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterExtendedEntity;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterExtendedEntitySerializer implements TwitterSerializer
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
     * @var TwitterVariantMediaSerializer
     */
    private $variantMediaSerializer;

    /**
     * Constructor
     *
     * @param TwitterEntityIndicesSerializer $entityIndicesSerializer
     * @param TwitterMediaSizeSerializer $mediaSizeSerializer
     * @param TwitterVariantMediaSerializer $variantMediaSerializer
     */
    public function __construct(
        TwitterEntityIndicesSerializer $entityIndicesSerializer,
        TwitterMediaSizeSerializer     $mediaSizeSerializer,
        TwitterVariantMediaSerializer  $variantMediaSerializer
    ) {
        $this->entityIndicesSerializer = $entityIndicesSerializer;
        $this->mediaSizeSerializer = $mediaSizeSerializer;
        $this->variantMediaSerializer = $variantMediaSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterExtendedEntity)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterExtendedEntity');
        }

        $extendedEntity = new \stdClass();
        $extendedEntity->id = $object->getId();
        $extendedEntity->media_url = $object->getMediaUrl();
        $extendedEntity->media_url_https = $object->getMediaUrlHttps();
        $extendedEntity->url = $object->getUrl();
        $extendedEntity->display_url = $object->getDisplayUrl();
        $extendedEntity->expanded_url = $object->getExpandedUrl();
        $extendedEntity->type = $object->getType();
        $extendedEntity->video_info = $object->getVideoInfo();
        $extendedEntity->duration_millis = $object->getDurationMillis();
        $extendedEntity->indices = $this->entityIndicesSerializer->serialize($object->getIndices());

        $extendedEntity->sizes = [];
        foreach ($object->getSizes() as $size) {
            $extendedEntity->sizes[$size->getName()] = $this->mediaSizeSerializer->serialize($size);
        }

        $extendedEntity->variants = [];
        foreach ($object->getVariants() as $variant) {
            $extendedEntity->variants[] = $this->variantMediaSerializer->serialize($variant);
        }

        return $extendedEntity;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterExtendedEntity
     */
    public function unserialize($obj, array $context = [])
    {
        $sizesObjects = [];
        if ($obj->sizes) {
            foreach ($obj->sizes as $sizeName => $sizeObj) {
                $sizesObjects[$sizeName] = $this->mediaSizeSerializer->unserialize(
                    $sizeObj,
                    [TwitterMediaSizeSerializer::NAME_VAR => $sizeName]
                );
            }
        }

        $variantObjects = [];
        if ($obj->variants) {
            foreach ($obj->variants as $variant) {
                $variantObjects[] = $this->variantMediaSerializer->unserialize($variant);
            }
        }

        return TwitterExtendedEntity::create(
            $obj->id,
            $obj->media_url,
            $obj->media_url_https,
            $obj->url,
            $obj->display_url,
            $obj->expanded_url,
            $sizesObjects,
            $obj->type,
            $obj->video_info,
            $obj->duration_millis,
            $variantObjects,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }

    /**
     * @return TwitterExtendedEntitySerializer
     */
    public static function build()
    {
        return new self(
            TwitterEntityIndicesSerializer::build(),
            TwitterMediaSizeSerializer::build(),
            TwitterVariantMediaSerializer::build()
        );
    }
}
