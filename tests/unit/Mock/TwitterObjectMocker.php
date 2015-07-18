<?php
namespace Twitter\Test\Mock;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterCoordinates;
use Twitter\Object\TwitterDelete;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterDisconnect;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterEvent;
use Twitter\Object\TwitterExtendedEntity;
use Twitter\Object\TwitterFriends;
use Twitter\Object\TwitterHashtag;
use Twitter\Object\TwitterMedia;
use Twitter\Object\TwitterMediaSize;
use Twitter\Object\TwitterPlace;
use Twitter\Object\TwitterSymbol;
use Twitter\Object\TwitterUrl;
use Twitter\Object\TwitterUser;
use Twitter\Object\TwitterUserMention;
use Twitter\Object\TwitterVariantMedia;
use Twitter\TwitterEventTarget;
use Twitter\TwitterMessage;
use Twitter\TwitterObject;
use TwitterStream\API\REST\TwitterRestApi;

trait TwitterObjectMocker {

    /**
     * @return TwitterObject
     */
    public function getTwitterObject() {
        return \Mockery::mock('\\Twitter\\TwitterObject');
    }

    /**
     * @return TwitterEventTarget
     */
    public function getTwitterEventTarget() {
        return \Mockery::mock('\\Twitter\\TwitterEventTarget');
    }

    /**
     * @param  int             $id
     * @param  string          $text
     * @param  TwitterUser     $sender
     * @param  TwitterEntities $entities
     * @param  \DateTime       $date
     * @return TwitterMessage
     */
    public function getTwitterMessage($id = null, $text = null, TwitterUser $sender = null, TwitterEntities $entities = null, \DateTime $date = null)
    {
        $twitterMessage = \Mockery::mock('\\Twitter\\TwitterMessage');
        $twitterMessage->shouldReceive('getId')->andReturn($id);
        $twitterMessage->shouldReceive('getText')->andReturn($text);
        $twitterMessage->shouldReceive('getEntities')->andReturn($entities);
        $twitterMessage->shouldReceive('getSender')->andReturn($sender);
        $twitterMessage->shouldReceive('getDate')->andReturn($date);

        return $twitterMessage;
    }

    /**
     * @param  int             $id
     * @param  string          $text
     * @param  TwitterUser     $sender
     * @param  TwitterEntities $entities
     * @return Tweet
     */
    public function getTweet($id = null, $text = null, TwitterUser $sender = null, TwitterEntities $entities = null) {
        $tweet = \Mockery::mock('\\Twitter\\Object\\Tweet');
        $tweet->shouldReceive('getId')->andReturn($id);
        if ($text) { $tweet->shouldReceive('getText')->andReturn($text); }
        if ($text) { $tweet->shouldReceive('getEntities')->andReturn($entities); }
        if ($text) { $tweet->shouldReceive('getSender')->andReturn($sender); }

        return $tweet;
    }

    /**
     * @param  int             $id
     * @param  string          $text
     * @param  TwitterUser     $sender
     * @param  TwitterEntities $entities
     * @return TwitterDirectMessage
     */
    public function getDirectMessage($id = null, $text = null, TwitterUser $sender = null, TwitterEntities $entities = null)
    {
        $dm = \Mockery::mock('\\Twitter\\Object\\TwitterDirectMessage');
        $dm->shouldReceive('getId')->andReturn($id);
        if ($text) { $dm->shouldReceive('getText')->andReturn($text); }
        if ($text) { $dm->shouldReceive('getEntities')->andReturn($entities); }
        if ($text) { $dm->shouldReceive('getSender')->andReturn($sender); }

        return $dm;
    }

    /**
     * @param  TwitterHashtag[]     $hashtags
     * @param  TwitterUserMention[] $userMentions
     * @return TwitterEntities
     */
    public function getTwitterEntities(array $hashtags = array(), array $userMentions = array())
    {
        $twitterMessage = \Mockery::mock('\\Twitter\\Object\\TwitterEntities');

        if ($hashtags) {
            $twitterMessage->shouldReceive('getHashtags')->andReturn($hashtags);
        }

        if ($userMentions) {
            $twitterMessage->shouldReceive('getUserMentions')->andReturn($userMentions);
        }

        return $twitterMessage;
    }

    /**
     * @param  string $text
     * @return TwitterHashtag
     */
    public function getHashTag($text)
    {
        $twitterHashtag = \Mockery::mock('\\Twitter\\Object\\TwitterHashtag');
        $twitterHashtag->shouldReceive('getText')->andReturn($text);
        $twitterHashtag->shouldReceive('__toString')->andReturn('#'.$text);

        return $twitterHashtag;
    }

    /**
     * @param  string $userName
     * @return TwitterUserMention
     */
    public function getUserMention($userName = null)
    {
        $twitterUserMention = \Mockery::mock('\\Twitter\\Object\\TwitterUserMention');
        $twitterUserMention->shouldReceive('getName')->andReturn($userName);
        $twitterUserMention->shouldReceive('getScreenName')->andReturn($userName);
        $twitterUserMention->shouldReceive('__toString')->andReturn('@'.$userName);

        return $twitterUserMention;
    }

    /**
     * @param  int    $id
     * @param  string $name
     * @return TwitterUser
     */
    public function getTwitterUser($id, $name) {
        $twitterUser = \Mockery::mock('\\Twitter\\Object\\TwitterUser');
        $twitterUser->shouldReceive('getId')->andReturn($id);
        $twitterUser->shouldReceive('getName')->andReturn($name);
        $twitterUser->shouldReceive('getScreenName')->andReturn($name);
        $twitterUser->shouldReceive('__toString')->andReturn('@'.$name);

        return $twitterUser;
    }

    /**
     * @param  TwitterUser $dmUser
     * @param  string      $dmMessage
     * @param  string      $tweetMessage
     * @param  mixed       $context
     * @return TwitterRestApi
     */
    public function getTwitterRestApi(TwitterUser $dmUser = null, $dmMessage = null, $tweetMessage = null, $context = null)
    {
        $tra = \Mockery::mock('\\TwitterStream\\API\\REST\\TwitterRestApi');

        if ($dmUser && $dmMessage) { $tra->shouldReceive('sendDirectMessage')->with($dmUser, $dmMessage)->once() ;}
        else { $tra->shouldReceive('sendDirectMessage'); }

        if ($tweetMessage) { $tra->shouldReceive('sendTweet')->with($tweetMessage, $context)->once(); }
        else { $tra->shouldReceive('sendTweet'); }

        return $tra;
    }

    /**
     * @return TwitterPlace
     */
    public function getPlace()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterPlace');
    }

    /**
     * @return TwitterCoordinates
     */
    public function getCoordinates()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterCoordinates');
    }

    /**
     * @return TwitterMediaSize
     */
    public function getTwitterMediaSize()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterMediaSize');
    }

    /**
     * @return TwitterEntityIndices
     */
    public function getTwitterEntityIndices()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterEntityIndices');
    }

    /**
     * @return TwitterVariantMedia
     */
    public function getVariantMedia()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterVariantMedia');
    }

    /**
     * @return TwitterSymbol
     */
    public function getSymbol()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterSymbol');
    }

    /**
     * @return TwitterMedia
     */
    public function getMedia()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterMedia');
    }

    /**
     * @return TwitterMediaSize
     */
    public function getMediaSize()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterMediaSize');
    }

    /**
     * @return TwitterUrl
     */
    public function getUrl()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterUrl');
    }

    /**
     * @return TwitterExtendedEntity
     */
    public function getExtendedEntity()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterExtendedEntity');
    }

    /**
     * @return TwitterEntityIndices
     */
    public function getIndices()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterEntityIndices');
    }

    /**
     * @return TwitterDelete
     */
    public function getDelete()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterDelete');
    }

    /**
     * @return TwitterDisconnect
     */
    public function getDisconnect()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterDisconnect');
    }

    /**
     * @return TwitterEvent
     */
    public function getEvent()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterEvent');
    }

    /**
     * @return TwitterFriends
     */
    public function getFriends()
    {
        return \Mockery::mock('\\Twitter\\Object\\TwitterFriends');
    }
} 