<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterUser;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterUserSerializer implements TwitterSerializer
{
    /**
     * Serialize a twitter user
     *
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterUser');
        }

        $user = new \stdClass();
        $user->id = $object->getId();
        $user->screen_name = $object->getScreenName();
        $user->name = $object->getName();
        $user->lang = $object->getLang();
        $user->location = $object->getLocation();
        $user->profile_background_image_url = $object->getProfileImageUrl();
        $user->profile_background_image_url_https = $object->getProfileImageUrlHttps();

        return $user;
    }

    /**
     * Unserialize a twitter user
     *
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterUser
     */
    public function unserialize($obj, array $context = [])
    {
        if (!$this->canUnserialize($obj)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        return TwitterUser::create(
            $obj->id,
            $obj->screen_name,
            $obj->name,
            $obj->lang,
            $obj->location,
            $obj->profile_background_image_url,
            $obj->profile_background_image_url_https
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterUser;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->id) &&
            isset($object->screen_name) &&
            isset($object->name) &&
            isset($object->lang);
    }

    /**
     * @return TwitterUserSerializer
     */
    public static function build()
    {
        return new self();
    }
}
