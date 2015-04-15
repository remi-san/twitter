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
    public function __construct(TwitterEntityIndicesSerializer $entityIndicesSerializer, TwitterMediaSizeSerializer $mediaSizeSerializer, TwitterVariantMediaSerializer $variantMediaSerializer)
    {
        $this->entityIndicesSerializer = $entityIndicesSerializer;
        $this->mediaSizeSerializer = $mediaSizeSerializer;
        $this->variantMediaSerializer = $variantMediaSerializer;
    }

    /**
     * @param  \Twitter\TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterExtendedEntity)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterExtendedEntity');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array $context
     * @return \Twitter\Object\TwitterExtendedEntity
     */
    public function unserialize($obj, array $context = array())
    {
        $sizesObjects = array();
        if ($obj->sizes) {
            foreach ($obj->sizes as $sizeName => $sizeObj) {
                $sizesObjects[$sizeName] = $this->mediaSizeSerializer->unserialize($sizeObj, array(TwitterMediaSizeSerializer::NAME_VAR => $sizeName));
            }
        }

        $variantObjects = array();
        if ($obj->variants) {
            foreach ($obj->variants as $variant) {
                $variantObjects[] = $this->variantMediaSerializer->unserialize($variant);
            }
        }

        return new TwitterExtendedEntity(
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
}