<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\TwitterDelete;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterDeleteSerializer implements TwitterSerializer
{
    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var TwitterDelete $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of TwitterDelete');

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
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        $d = $obj->delete;
        if (isset($d->status)) {
            $ref = $d->status;
            $type = TwitterDelete::TWEET;
        } else {
            $ref = $d->direct_message;
            $type = TwitterDelete::DM;
        }

        return TwitterDelete::create(
            $type,
            $ref->id,
            $ref->user_id,
            (new \DateTimeImmutable())->setTimestamp((int) floor($d->timestamp_ms / 1000))?:new \DateTimeImmutable()
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterDelete;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->delete);
    }

    /**
     * @return TwitterDeleteSerializer
     */
    public static function build()
    {
        return new self();
    }
}
