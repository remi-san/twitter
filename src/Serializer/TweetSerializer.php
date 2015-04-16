<?php
namespace Twitter\Serializer;

use Twitter\Object\Tweet;
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
    function __construct(
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

        throw new \BadMethodCallException('Not Implemented');
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
} 