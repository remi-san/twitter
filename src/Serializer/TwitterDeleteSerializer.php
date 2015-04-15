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

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterDelete
     */
    public function unserialize($obj, array $context = array())
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

        $date = new \DateTime();
        return new TwitterDelete(
            $type,
            $ref->id,
            $ref->user_id,
            isset($d->timestamp_ms) ? $date->setTimestamp(floor($d->timestamp_ms / 1000)) : null
        );
    }
} 