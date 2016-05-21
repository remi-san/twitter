<?php
namespace Twitter\Test\Object;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterUser;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\TwitterMessageId;

class TweetTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker;

    /**
     * @var TwitterUser
     */
    private $sender;

    /**
     * @var TwitterUser
     */
    private $recipient;

    /**
     * @var TwitterEntities
     */
    private $entities;


    public function setUp()
    {
        $this->sender    = $this->getTwitterUser(42, 'Douglas');
        $this->recipient = $this->getTwitterUser(666, 'Satan');
        $this->entities  = $this->getTwitterEntities();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testTweetConstructor()
    {
        $id = TwitterMessageId::create(42);
        $text = 'Message';
        $lang = 'fr';
        $createdAt = new \DateTime();
        $coordinates = null;
        $place = null;
        $inReplyToStatusId = 314;
        $inReplyToUserId = $this->recipient->getId();
        $inReplyToScreenName = $this->recipient->getScreenName();
        $retweeted = false;
        $retweetCount = 0;
        $favorited = false;
        $favoriteCount = false;
        $truncated = false;
        $source = null;
        $retweetedStatus = $this->getTweet();

        $tweet = Tweet::create(
            $id,
            $this->sender,
            $text,
            $lang,
            $createdAt,
            $this->entities,
            $coordinates,
            $place,
            $inReplyToStatusId,
            $inReplyToUserId,
            $inReplyToScreenName,
            $retweeted,
            $retweetCount,
            $favorited,
            $favoriteCount,
            $truncated,
            $source,
            $retweetedStatus
        );

        $this->assertEquals($id, $tweet->getId());
        $this->assertEquals($this->sender, $tweet->getSender());
        $this->assertEquals($text, $tweet->getText());
        $this->assertEquals($lang, $tweet->getLang());
        $this->assertEquals($this->entities, $tweet->getEntities());
        $this->assertEquals($coordinates, $tweet->getCoordinates());
        $this->assertEquals($place, $tweet->getPlace());
        $this->assertEquals($inReplyToStatusId, $tweet->getInReplyToStatusId());
        $this->assertEquals($inReplyToUserId, $tweet->getInReplyToUserId());
        $this->assertEquals($inReplyToScreenName, $tweet->getInReplyToScreenName());
        $this->assertEquals($retweeted, $tweet->isRetweeted());
        $this->assertEquals($retweetCount, $tweet->getRetweetCount());
        $this->assertEquals($favorited, $tweet->isFavorited());
        $this->assertEquals($favoriteCount, $tweet->getFavoriteCount());
        $this->assertEquals($truncated, $tweet->isTruncated());
        $this->assertEquals($source, $tweet->getSource());
        $this->assertEquals($createdAt, $tweet->getDate());
        $this->assertEquals($retweetedStatus, $tweet->getRetweetedStatus());
        $this->assertEquals('Tweet ['.$tweet->getId().']', $tweet->__toString());
    }

    /**
     * @test
     */
    public function testEntities()
    {
        $hashtagText = 'plop';
        $hashtag = $this->getHashTag($hashtagText);

        $userName = 'roger';
        $userMention = $this->getUserMention(null, $userName);

        $id = TwitterMessageId::create(42);
        $coreText = 'message';
        $text = '@'.$userName.' '.$coreText.' #'.$hashtagText;
        $lang = 'fr';
        $createdAt = new \DateTime();

        $this->entities->shouldReceive('getHashtags')->withNoArgs()->andReturn(array($hashtag));
        $this->entities->shouldReceive('getUserMentions')->withNoArgs()->andReturn(array($userMention));

        $tweet = Tweet::create(
            $id,
            $this->sender,
            $text,
            $lang,
            $createdAt,
            $this->entities
        );

        $this->assertEquals(array('#'.$hashtagText), $tweet->getFormattedHashtags());
        $this->assertEquals(array('@'.$userName), $tweet->getFormattedUserMentions());
        $this->assertEquals($coreText, $tweet->getStrippedText());
        $this->assertTrue($tweet->containsHashtag($hashtagText));
        $this->assertFalse($tweet->containsHashtag('dummy'));
    }
}
