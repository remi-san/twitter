<?php

namespace Twitter\Serializer;

use Assert\Assertion;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterDate;
use Twitter\Object\TwitterEntities;
use Twitter\TwitterMessageId;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TweetSerializer implements TwitterSerializer
{
    /**
     * @var TwitterUserSerializer
     */
    private $userSerializer;

    /**
     * @var TwitterEntitiesSerializer
     */
    private $twitterEntitiesSerializer;

    /**
     * @var TwitterCoordinatesSerializer
     */
    private $coordinatesSerializer;

    /**
     * @var TwitterPlaceSerializer
     */
    private $placeSerializer;

    /**
     * @param TwitterUserSerializer        $userSerializer
     * @param TwitterEntitiesSerializer    $twitterEntitiesSerializer
     * @param TwitterCoordinatesSerializer $coordinatesSerializer
     * @param TwitterPlaceSerializer       $placeSerializer
     */
    public function __construct(
        TwitterUserSerializer $userSerializer,
        TwitterEntitiesSerializer $twitterEntitiesSerializer,
        TwitterCoordinatesSerializer $coordinatesSerializer,
        TwitterPlaceSerializer $placeSerializer
    ) {
        $this->userSerializer = $userSerializer;
        $this->twitterEntitiesSerializer = $twitterEntitiesSerializer;
        $this->coordinatesSerializer = $coordinatesSerializer;
        $this->placeSerializer = $placeSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        /* @var Tweet $object */
        Assertion::true($this->canSerialize($object), 'object must be an instance of Tweet');
        Assertion::eq(new \DateTimeZone('UTC'), $object->getDate()->getTimezone());

        $tweet = new \stdClass();
        $tweet->id = (string) $object->getId();
        $tweet->user = $this->userSerializer->serialize($object->getSender());
        $tweet->text = $object->getText();
        $tweet->lang = $object->getLang();
        $tweet->created_at = $object->getDate()->format(TwitterDate::FORMAT);
        $tweet->entities = $this->twitterEntitiesSerializer->serialize($object->getEntities());
        $tweet->coordinates = $object->getCoordinates() ?
            $this->coordinatesSerializer->serialize($object->getCoordinates()):
            null;
        $tweet->place = $object->getPlace() ?
            $this->placeSerializer->serialize($object->getPlace()):
            null;
        $tweet->in_reply_to_status_id = $object->getInReplyToStatusId();
        $tweet->in_reply_to_user_id = $object->getInReplyToUserId();
        $tweet->in_reply_to_screen_name = $object->getInReplyToScreenName();
        $tweet->retweeted = $object->isRetweeted();
        $tweet->retweet_count = $object->getRetweetCount();
        $tweet->favorited = $object->isFavorited();
        $tweet->favorite_count = $object->getFavoriteCount();
        $tweet->truncated = $object->isTruncated();
        $tweet->source = $object->getSource();

        if ($object->getRetweetedStatus()) {
            $tweet->retweeted_status = $this->serialize($object->getRetweetedStatus());
        }

        return $tweet;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\Tweet
     */
    public function unserialize($obj, array $context = [])
    {
        Assertion::true($this->canUnserialize($obj), 'object is not unserializable');

        $createdAt = new \DateTimeImmutable($obj->created_at);
        Assertion::eq(new \DateTimeZone('UTC'), $createdAt->getTimezone());

        return Tweet::create(
            TwitterMessageId::create($obj->id),
            $this->userSerializer->unserialize($obj->user),
            $obj->text,
            $obj->lang,
            $createdAt,
            $obj->entities?$this->twitterEntitiesSerializer->unserialize($obj->entities):TwitterEntities::create(),
            $obj->coordinates?$this->coordinatesSerializer->unserialize($obj->coordinates):null,
            $obj->place?$this->placeSerializer->unserialize($obj->place):null,
            $obj->in_reply_to_status_id,
            $obj->in_reply_to_user_id,
            $obj->in_reply_to_screen_name,
            $obj->retweeted,
            $obj->retweet_count,
            $obj->favorited,
            $obj->favorite_count,
            $obj->truncated,
            $obj->source,
            (isset($obj->retweeted_status)) ? $this->unserialize($obj->retweeted_status) : null
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof Tweet;
    }

    /**
     * @param  \stdClass $obj
     * @param  array $context
     * @return boolean
     */
    public function canUnserialize($obj, array $context = [])
    {
        return isset($obj->text) && isset($obj->user);
    }

    /**
     * @return TweetSerializer
     */
    public static function build()
    {
        return new self(
            TwitterUserSerializer::build(),
            TwitterEntitiesSerializer::build(),
            TwitterCoordinatesSerializer::build(),
            TwitterPlaceSerializer::build()
        );
    }
}
