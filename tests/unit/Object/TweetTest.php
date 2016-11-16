<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Faker\Generator;
use Mockery\Mock;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterCoordinates;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterHashtag;
use Twitter\Object\TwitterPlace;
use Twitter\Object\TwitterUser;
use Twitter\Object\TwitterUserMention;
use Twitter\TwitterMessageId;

class TweetTest extends \PHPUnit_Framework_TestCase
{
    /** @var Generator */
    private $faker;

    /** @var TwitterMessageId */
    private $id;

    /** @var string */
    private $lang;

    /** @var string */
    private $userName;

    /** @var string */
    private $hashtagText;

    /** @var string */
    private $text;

    /** @var string */
    private $complexText;

    /** @var \DateTimeInterface */
    private $date;

    /** @var TwitterCoordinates */
    private $coordinates;

    /** @var TwitterPlace */
    private $place;

    /** @var int */
    private $inReplyToStatusId;

    /** @var int */
    private $inReplyToUserId;

    /** @var string */
    private $inReplyToScreenName;

    /** @var bool */
    private $retweeted;

    /** @var int */
    private $retweetCount;

    /** @var bool */
    private $favorited;

    /** @var int */
    private $favoriteCount;

    /** @var bool */
    private $truncated;

    /** @var string */
    private $source;

    /** @var Tweet */
    private $retweetedStatus;

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
        $this->lang = $this->faker->countryISOAlpha3;
        $this->userName = $this->faker->userName;
        $this->hashtagText = $this->faker->word;
        $this->text = $this->faker->text();
        $this->complexText = '@' . $this->userName . ' ' . $this->text . ' #' . $this->hashtagText;
        $this->date = new \DateTimeImmutable();

        $this->coordinates = \Mockery::mock(TwitterCoordinates::class);
        $this->place = \Mockery::mock(TwitterPlace::class);
        $this->inReplyToStatusId = $this->faker->randomNumber();
        $this->inReplyToUserId = $this->faker->randomNumber();
        $this->inReplyToScreenName = $this->faker->userName;
        $this->retweeted = $this->faker->boolean();
        $this->retweetCount = $this->faker->randomNumber();
        $this->favorited = $this->faker->boolean();
        $this->favoriteCount = $this->faker->randomNumber();
        $this->truncated = $this->faker->boolean();
        $this->source = null;
        $this->retweetedStatus = \Mockery::mock(Tweet::class);

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
    public function itShouldBuildTheObject()
    {
        $tweet = Tweet::create(
            $this->id,
            $this->sender,
            $this->text,
            $this->lang,
            $this->date,
            $this->entities,
            $this->coordinates,
            $this->place,
            $this->inReplyToStatusId,
            $this->inReplyToUserId,
            $this->inReplyToScreenName,
            $this->retweeted,
            $this->retweetCount,
            $this->favorited,
            $this->favoriteCount,
            $this->truncated,
            $this->source,
            $this->retweetedStatus
        );

        $this->assertEquals($this->id, $tweet->getId());
        $this->assertEquals($this->sender, $tweet->getSender());
        $this->assertEquals($this->text, $tweet->getText());
        $this->assertEquals($this->lang, $tweet->getLang());
        $this->assertEquals($this->entities, $tweet->getEntities());
        $this->assertEquals($this->coordinates, $tweet->getCoordinates());
        $this->assertEquals($this->place, $tweet->getPlace());
        $this->assertEquals($this->inReplyToStatusId, $tweet->getInReplyToStatusId());
        $this->assertEquals($this->inReplyToUserId, $tweet->getInReplyToUserId());
        $this->assertEquals($this->inReplyToScreenName, $tweet->getInReplyToScreenName());
        $this->assertEquals($this->retweeted, $tweet->isRetweeted());
        $this->assertEquals($this->retweetCount, $tweet->getRetweetCount());
        $this->assertEquals($this->favorited, $tweet->isFavorited());
        $this->assertEquals($this->favoriteCount, $tweet->getFavoriteCount());
        $this->assertEquals($this->truncated, $tweet->isTruncated());
        $this->assertEquals($this->source, $tweet->getSource());
        $this->assertEquals($this->date, $tweet->getDate());
        $this->assertEquals($this->retweetedStatus, $tweet->getRetweetedStatus());
        $this->assertEquals('Tweet ['.$tweet->getId().']', $tweet->__toString());
    }

    /**
     * @test
     */
    public function itShouldBuildTheObjectWithEntities()
    {
        $this->messageContainsHashtag();
        $this->messageContainsUserMention();

        $tweet = Tweet::create(
            $this->id,
            $this->sender,
            $this->complexText,
            $this->lang,
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
