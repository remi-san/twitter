<?php
namespace Twitter\Serializer;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterDate;
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
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof Tweet)) {
            throw new \InvalidArgumentException('$object must be an instance of Tweet');
        }

        $tweet = new \stdClass();
        $tweet->id = $object->getId();
        $tweet->user = $this->userSerializer->serialize($object->getSender());
        $tweet->text = $object->getText();
        $tweet->lang = $object->getLang();
        $tweet->created_at = $object->getDate()->setTimezone(new \DateTimeZone('UTC'))->format(TwitterDate::FORMAT);
        $tweet->entities = $object->getEntities()?
            $this->twitterEntitiesSerializer->serialize($object->getEntities()):
            array();
        $tweet->coordinates = $object->getCoordinates()?
            $this->coordinatesSerializer->serialize($object->getCoordinates()):
            null;
        $tweet->place = $object->getPlace()?$this->placeSerializer->serialize($object->getPlace()):null;
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
    public function unserialize($obj, array $context = array())
    {
        return new Tweet(
            $obj->id,
            $this->userSerializer->unserialize($obj->user),
            $obj->text,
            $obj->lang,
            new \DateTime($obj->created_at),
            $obj->entities?$this->twitterEntitiesSerializer->unserialize($obj->entities):null,
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
