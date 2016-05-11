<?php

namespace Twitter\Serializer;

use Twitter\Serializer;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterJsonSerializer implements Serializer
{
    /**
     * @var TwitterSerializer[]
     */
    private $serializers;

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
        $this->serializers = [];
        $this->serializers[] = $twitterTargetSerializer;
        $this->serializers[] = $directMessageSerializer;
        $this->serializers[] = $twitterEventSerializer;
        $this->serializers[] = $twitterFriendsSerializer;
        $this->serializers[] = $twitterDisconnectSerializer;
        $this->serializers[] = $twitterDeleteSerializer;
        $this->serializers[] = $twitterUserSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return string
     */
    public function serialize(TwitterSerializable $object)
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->canSerialize($object)) {
                return json_encode($serializer->serialize($object));
            }
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  string $string
     * @return TwitterSerializable
     */
    public function unserialize($string)
    {
        $object = json_decode($string);

        foreach ($this->serializers as $serializer) {
            if ($serializer->canUnserialize($object)) {
                return $serializer->unserialize($object);
            }
        }

        throw new \BadMethodCallException('Not Implemented');
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
