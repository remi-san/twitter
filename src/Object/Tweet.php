<?php

namespace Twitter\Object;

use Twitter\TwitterEventTarget;
use Twitter\TwitterMessage;
use Twitter\TwitterMessageId;

class Tweet extends AbstractMessage implements TwitterEventTarget, TwitterMessage
{
    /**
     * @var string
     */
    private $lang;

    /**
     * @var TwitterCoordinates
     */
    private $coordinates;

    /**
     * @var TwitterPlace
     */
    private $place;

    /**
     * @var bool
     */
    private $retweeted;

    /**
     * @var int
     */
    private $inReplyToStatusId;

    /**
     * @var int
     */
    private $inReplyToUserId;

    /**
     * @var string
     */
    private $inReplyToScreenName;

    /**
     * @var int
     */
    private $retweetCount;

    /**
     * @var int
     */
    private $favoriteCount;

    /**
     * @var string
     */
    private $source;

    /**
     * @var bool
     */
    private $favorited;

    /**
     * @var boolean
     */
    private $truncated;

    /**
     * @var Tweet
     */
    private $retweetedStatus;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return TwitterCoordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @return int
     */
    public function getFavoriteCount()
    {
        return $this->favoriteCount;
    }

    /**
     * @return boolean
     */
    public function isFavorited()
    {
        return $this->favorited;
    }

    /**
     * @return string
     */
    public function getInReplyToScreenName()
    {
        return $this->inReplyToScreenName;
    }

    /**
     * @return int
     */
    public function getInReplyToStatusId()
    {
        return $this->inReplyToStatusId;
    }

    /**
     * @return int
     */
    public function getInReplyToUserId()
    {
        return $this->inReplyToUserId;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return TwitterPlace
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return int
     */
    public function getRetweetCount()
    {
        return $this->retweetCount;
    }

    /**
     * @return boolean
     */
    public function isRetweeted()
    {
        return $this->retweeted;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return boolean
     */
    public function isTruncated()
    {
        return $this->truncated;
    }

    /**
     * @return Tweet
     */
    public function getRetweetedStatus()
    {
        return $this->retweetedStatus;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Tweet [' . $this->id . ']';
    }

    /**
     * Static constructor.
     *
     * @param TwitterMessageId   $id
     * @param TwitterUser        $sender
     * @param string             $text
     * @param string             $lang
     * @param \DateTimeInterface $createdAt
     * @param TwitterEntities    $entities
     * @param TwitterCoordinates $coordinates
     * @param TwitterPlace       $place
     * @param int                $inReplyToStatusId
     * @param int                $inReplyToUserId
     * @param string             $inReplyToScreenName
     * @param bool               $retweeted
     * @param int                $retweetCount
     * @param bool               $favorited
     * @param bool               $favoriteCount
     * @param bool               $truncated
     * @param null               $source
     * @param Tweet              $retweetedStatus
     *
     * @return Tweet
     */
    public static function create(
        TwitterMessageId $id,
        TwitterUser $sender,
        $text,
        $lang,
        \DateTimeInterface $createdAt,
        TwitterEntities $entities = null,
        TwitterCoordinates $coordinates = null,
        TwitterPlace $place = null,
        $inReplyToStatusId = null,
        $inReplyToUserId = null,
        $inReplyToScreenName = null,
        $retweeted = false,
        $retweetCount = 0,
        $favorited = false,
        $favoriteCount = false,
        $truncated = false,
        $source = null,
        Tweet $retweetedStatus = null
    ) {
        $obj = new self();

        $obj->init($id, $sender, $text, $entities, $createdAt);

        $obj->lang = $lang;
        $obj->coordinates = $coordinates;
        $obj->place = $place;
        $obj->inReplyToStatusId = $inReplyToStatusId;
        $obj->inReplyToUserId = $inReplyToUserId;
        $obj->inReplyToScreenName = $inReplyToScreenName;
        $obj->retweeted = $retweeted;
        $obj->retweetCount = $retweetCount;
        $obj->favorited = $favorited;
        $obj->favoriteCount = $favoriteCount;
        $obj->truncated = $truncated;
        $obj->source = $source;
        $obj->retweetedStatus = $retweetedStatus;

        return $obj;
    }
}
