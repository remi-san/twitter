<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterDelete;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterDeleteSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterDelete)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterDelete');
        }

        $refObject = new \stdClass();
        $refObject->id = $object->getId();
        $refObject->user_id = $object->getUserId();

        $obj = new \stdClass();

        switch ($object->getType()) {
            case TwitterDelete::TWEET:
                $obj->status = $refObject;
                break;
            case TwitterDelete::DM:
                $obj->direct_message = $refObject;
                break;
            default:
                throw new \InvalidArgumentException('Invalid delete type');
        }

        if ($object->getDate()) {
            $obj->timestamp_ms = $object->getDate()->getTimestamp() * 1000;
        }

        $delete = new \stdClass();
        $delete->delete = $obj;

        return $delete;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterDelete
     */
    public function unserialize($obj, array $context = [])
    {
        $d = $obj->delete;
        $ref = null;
        $type = null;

        if (isset($d->status)) {
            $ref = $d->status;
            $type = TwitterDelete::TWEET;
        } else {
            $ref = $d->direct_message;
            $type = TwitterDelete::DM;
        }

        $date = new \DateTimeImmutable();
        return TwitterDelete::create(
            $type,
            $ref->id,
            $ref->user_id,
            isset($d->timestamp_ms) ? $date->setTimestamp(floor($d->timestamp_ms / 1000)) : null
        );
    }

    /**
     * @return TwitterDeleteSerializer
     */
    public static function build()
    {
        return new self();
    }
}
