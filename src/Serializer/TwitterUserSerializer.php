<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterUser;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterUserSerializer implements TwitterSerializer
{

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterUser)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterUser');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\TwitterUser
     */
    public function unserialize($obj, array $context = array())
    {
        return new TwitterUser(
            $obj->id,
            $obj->screen_name,
            $obj->name,
            $obj->lang,
            $obj->location,
            $obj->profile_background_image_url,
            $obj->profile_background_image_url_https
        );
    }
} 