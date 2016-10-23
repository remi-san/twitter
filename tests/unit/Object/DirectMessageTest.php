<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Faker\Generator;
use Mockery\Mock;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterHashtag;
use Twitter\Object\TwitterUser;
use Twitter\Object\TwitterUserMention;
use Twitter\TwitterMessageId;

class DirectMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var Generator */
    private $faker;

    /** @var TwitterMessageId */
    private $id;

    /** @var string */
    private $userName;

    /** @var string */
    private $hashtagText;

    /** @var string */
    private $text;

    /** @var string */
    private $complexText;

    /** @var \DateTimeImmutable */
    private $date;

    /** @var TwitterUser */
    private $sender;

    /** @var TwitterUser */
    private $recipient;

    /** @var TwitterHashtag | Mock */
    private $hashtag;

    /** @var TwitterUserMention | Mock */
    private $userMention;

    /** @var TwitterEntities | Mock */
    private $entities;

    public function setUp()
    {
        $this->faker = Factory::create();

        $this->id = TwitterMessageId::create($this->faker->uuid);
        $this->userName = $this->faker->userName;
        $this->hashtagText = $this->faker->word;
        $this->text = $this->faker->text();
        $this->complexText = '@' . $this->userName . ' ' . $this->text . ' #' . $this->hashtagText;
        $this->date = new \DateTimeImmutable();

        $this->hashtag = \Mockery::mock(TwitterHashtag::class);
        $this->userMention = \Mockery::mock(TwitterUserMention::class);

        $this->sender = \Mockery::mock(TwitterUser::class);
        $this->recipient = \Mockery::mock(TwitterUser::class);
        $this->entities = \Mockery::mock(TwitterEntities::class);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $dm = TwitterDirectMessage::create(
            $this->id,
            $this->sender,
            $this->recipient,
            $this->text,
            $this->date,
            $this->entities
        );

        $this->assertEquals($this->id, $dm->getId());
        $this->assertEquals($this->sender, $dm->getSender());
        $this->assertEquals($this->recipient, $dm->getRecipient());
        $this->assertEquals($this->text, $dm->getText());
        $this->assertEquals($this->date, $dm->getDate());
        $this->assertEquals($this->entities, $dm->getEntities());
        $this->assertEquals('DM ['.$this->id.']', $dm->__toString());
    }

    /**
     * @test
     */
    public function testEntities()
    {
        $this->messageContainsHashtag();
        $this->messageContainsUserMention();

        $tweet = TwitterDirectMessage::create(
            $this->id,
            $this->sender,
            $this->recipient,
            $this->complexText,
            $this->date,
            $this->entities
        );

        $this->assertEquals(array('#'.$this->hashtagText), $tweet->getFormattedHashtags());
        $this->assertEquals(array('@'.$this->userName), $tweet->getFormattedUserMentions());
        $this->assertEquals($this->text, $tweet->getStrippedText());
        $this->assertTrue($tweet->containsHashtag($this->hashtagText));
        $this->assertFalse($tweet->containsHashtag($this->faker->word));
    }

    private function messageContainsHashtag()
    {
        $this->hashtag->shouldReceive('getText')->andReturn($this->hashtagText);
        $this->hashtag->shouldReceive('__toString')->andReturn('#' . $this->hashtagText);
        $this->entities->shouldReceive('getHashtags')->withNoArgs()->andReturn(array($this->hashtag));
    }

    private function messageContainsUserMention()
    {
        $this->userMention->shouldReceive('__toString')->andReturn('@' . $this->userName);
        $this->entities->shouldReceive('getUserMentions')->withNoArgs()->andReturn(array($this->userMention));
    }
}
