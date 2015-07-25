<?php
namespace Twitter\Object;

use Twitter\TwitterEventTarget;
use Twitter\TwitterMessage;

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
     * Constructor
     *
     * @param int                $id
     * @param TwitterUser        $sender
     * @param string             $text
     * @param string             $lang
     * @param \DateTime          $createdAt
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
     */
    public function __construct(
        $id,
        TwitterUser $sender,
        $text,
        $lang,
        \DateTime $createdAt,
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

        parent::__construct($id, $sender, $text, $entities, $createdAt);

        $this->lang = $lang;
        $this->coordinates = $coordinates;
        $this->place = $place;
        $this->inReplyToStatusId = $inReplyToStatusId;
        $this->inReplyToUserId = $inReplyToUserId;
        $this->inReplyToScreenName = $inReplyToScreenName;
        $this->retweeted = $retweeted;
        $this->retweetCount = $retweetCount;
        $this->favorited = $favorited;
        $this->favoriteCount = $favoriteCount;
        $this->truncated = $truncated;
        $this->source = $source;
        $this->retweetedStatus = $retweetedStatus;
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
        return 'Tweet ['.$this->id.']';
    }
}
