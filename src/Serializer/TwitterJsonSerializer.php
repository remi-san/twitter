<?php

namespace Twitter\Serializer;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterUser;
use Twitter\Serializer;
use Twitter\TwitterSerializable;

class TwitterJsonSerializer implements Serializer
{
    /**
     * @var TwitterEventTargetSerializer
     */
    private $twitterTargetSerializer;

    /**
     * @var TwitterDirectMessageSerializer
     */
    private $directMessageSerializer;

    /**
     * @var TwitterEventSerializer
     */
    private $twitterEventSerializer;

    /**
     * @var TwitterFriendsSerializer
     */
    private $twitterFriendsSerializer;

    /**
     * @var TwitterDisconnectSerializer
     */
    private $twitterDisconnectSerializer;

    /**
     * @var TwitterDeleteSerializer
     */
    private $twitterDeleteSerializer;

    /**
     * @var TwitterUserSerializer
     */
    private $twitterUserSerializer;

    /**
     * Constructor
     *
     * @param TwitterEventTargetSerializer $twitterTargetSerializer
     * @param TwitterDirectMessageSerializer $directMessageSerializer
     * @param TwitterEventSerializer $twitterEventSerializer
     * @param TwitterFriendsSerializer $twitterFriendsSerializer
     * @param TwitterDisconnectSerializer $twitterDisconnectSerializer
     * @param TwitterDeleteSerializer $twitterDeleteSerializer
     * @param TwitterUserSerializer $twitterUserSerializer
     */
    public function __construct(
        TwitterEventTargetSerializer $twitterTargetSerializer,
        TwitterDirectMessageSerializer $directMessageSerializer,
        TwitterEventSerializer $twitterEventSerializer,
        TwitterFriendsSerializer $twitterFriendsSerializer,
        TwitterDisconnectSerializer $twitterDisconnectSerializer,
        TwitterDeleteSerializer $twitterDeleteSerializer,
        TwitterUserSerializer $twitterUserSerializer
    ) {
        $this->twitterTargetSerializer = $twitterTargetSerializer;
        $this->directMessageSerializer = $directMessageSerializer;
        $this->twitterEventSerializer = $twitterEventSerializer;
        $this->twitterFriendsSerializer = $twitterFriendsSerializer;
        $this->twitterDisconnectSerializer = $twitterDisconnectSerializer;
        $this->twitterDeleteSerializer = $twitterDeleteSerializer;
        $this->twitterUserSerializer = $twitterUserSerializer;
    }

    /**
     * @param  object $object
     * @return string
     */
    public function serialize($object)
    {
        if (!($object instanceof TwitterSerializable)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterSerializable');
        }

        $serializedObject = null;

        if ($object instanceof TwitterUser) {
            $serializedObject = $this->twitterUserSerializer->serialize($object);
        } elseif ($object instanceof Tweet) { // or list
            $serializedObject =  $this->twitterTargetSerializer->serialize($object);
        } elseif ($object instanceof TwitterDirectMessage) {
            $serializedObject = new \stdClass();
            $serializedObject->direct_message = $this->directMessageSerializer->serialize($object);
        } else {
            throw new \BadMethodCallException('Not Implemented');
        }

        return json_encode($serializedObject);
    }

    /**
     * @param  string $string
     * @return object
     */
    public function unserialize($string)
    {
        $obj = json_decode($string);

        $object = null;

        if (isset($obj->text) && isset($obj->user)) {
            $object = $this->twitterTargetSerializer->unserialize($obj);
        } elseif (isset($obj->direct_message)) {
            $object = $this->directMessageSerializer->unserialize($obj->direct_message);
        } elseif (isset($obj->event)) {
            $object = $this->twitterEventSerializer->unserialize($obj);
        } elseif (isset($obj->friends)) {
            $object = $this->twitterFriendsSerializer->unserialize($obj);
        } elseif (isset($obj->disconnect)) {
            $object = $this->twitterDisconnectSerializer->unserialize($obj);
        } elseif (isset($obj->delete)) {
            $object = $this->twitterDeleteSerializer->unserialize($obj);
        } elseif (isset($obj->screen_name)) {
            $object = $this->twitterUserSerializer->unserialize($obj);
        }

        return $object;
    }

    /**
     * @return TwitterJsonSerializer
     */
    public static function build()
    {
        return new self(
            TwitterEventTargetSerializer::build(),
            TwitterDirectMessageSerializer::build(),
            TwitterEventSerializer::build(),
            TwitterFriendsSerializer::build(),
            TwitterDisconnectSerializer::build(),
            TwitterDeleteSerializer::build(),
            TwitterUserSerializer::build()
        );
    }
}
