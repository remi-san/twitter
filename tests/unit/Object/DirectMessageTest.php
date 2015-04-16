<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterUser;
use Twitter\Test\Mock\TwitterObjectMocker;

class DirectMessageTest extends \PHPUnit_Framework_TestCase {
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

    /**
     * @test
     */
    public function testConstructor() {

        $id = 42;
        $text = 'Message';
        $date = new \DateTime();

        $dm = new TwitterDirectMessage($id, $this->sender, $this->recipient, $text, $date, $this->entities);

        $this->assertEquals($id, $dm->getId());
        $this->assertEquals($this->sender, $dm->getSender());
        $this->assertEquals($this->recipient, $dm->getRecipient());
        $this->assertEquals($text, $dm->getText());
        $this->assertEquals($date, $dm->getDate());
        $this->assertEquals($this->entities, $dm->getEntities());
        $this->assertEquals('DM ['.$id.']', $dm->__toString());
    }

    /**
     * @test
     */
    public function testEntities()
    {
        $hashtagText = 'plop';
        $hashtag = $this->getHashTag($hashtagText);

        $userName = 'roger';
        $userMention = $this->getUserMention($userName);

        $id = 42;
        $coreText = 'message';
        $text = '@'.$userName.' '.$coreText.' #'.$hashtagText;
        $createdAt = new \DateTime();

        $this->entities->shouldReceive('getHashtags')->withNoArgs()->andReturn(array($hashtag));
        $this->entities->shouldReceive('getUserMentions')->withNoArgs()->andReturn(array($userMention));

        $tweet = new TwitterDirectMessage(
            $id,
            $this->sender,
            $this->recipient,
            $text,
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