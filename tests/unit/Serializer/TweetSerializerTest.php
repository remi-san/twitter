<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterCoordinates;
use Twitter\Object\TwitterDate;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterPlace;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterCoordinatesSerializer;
use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterPlaceSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterMessageId;
use Twitter\TwitterSerializable;

class TweetSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TwitterMessageId */
    private $id;

    /** @var string */
    private $text;

    /** @var string */
    private $lang;

    /** @var \DateTimeInterface */
    private $date;

    /** @var int */
    private $replyStatusId;

    /** @var int */
    private $replyUserId;

    /** @var string */
    private $replyUserScreenName;

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

    /** @var TwitterUser */
    private $user;

    /** @var TwitterEntities */
    private $entities;

    /** @var TwitterCoordinates */
    private $coordinates;

    /** @var TwitterPlace */
    private $place;

    /** @var TwitterMessageId */
    private $retweetId;

    /** @var string */
    private $retweetText;

    /** @var object */
    private $userObj;

    /** @var object */
    private $entitiesObj;

    /** @var object */
    private $coordinatesObj;

    /** @var object */
    private $placeObj;

    /** @var TwitterUserSerializer | Mock */
    private $userSerializer;

    /** @var TwitterEntitiesSerializer | Mock */
    private $entitiesSerializer;

    /** @var TwitterCoordinatesSerializer | Mock */
    private $coordinatesSerializer;

    /** @var TwitterPlaceSerializer | Mock */
    private $placeSerializer;

    /** @var TweetSerializer */
    private $serializer;

    public function setUp()
    {
        $this->id = TwitterMessageId::create(666);
        $this->text = 'text';
        $this->lang = 'en';
        $this->date = new \DateTimeImmutable('2010-01-01 12:00:00 +00:00');
        $this->replyStatusId = 12;
        $this->replyUserId = 2048;
        $this->replyUserScreenName = 'gc';
        $this->retweeted = true;
        $this->retweetCount = 2;
        $this->favorited = false;
        $this->favoriteCount = 5;
        $this->truncated = true;
        $this->source = 'twitter';

        $this->user = \Mockery::mock(TwitterUser::class);
        $this->entities = \Mockery::mock(TwitterEntities::class);
        $this->coordinates = \Mockery::mock(TwitterCoordinates::class);
        $this->place = \Mockery::mock(TwitterPlace::class);

        $this->retweetId = TwitterMessageId::create(999);
        $this->retweetText = 'retweet text';

        $this->userObj = new \stdClass();
        $this->entitiesObj = new \stdClass();
        $this->coordinatesObj = new \stdClass();
        $this->placeObj = new \stdClass();

        $this->userSerializer = \Mockery::mock(TwitterUserSerializer::class);
        $this->entitiesSerializer = \Mockery::mock(TwitterEntitiesSerializer::class);
        $this->coordinatesSerializer = \Mockery::mock(TwitterCoordinatesSerializer::class);
        $this->placeSerializer = \Mockery::mock(TwitterPlaceSerializer::class);

        $this->serializer = new TweetSerializer(
            $this->userSerializer,
            $this->entitiesSerializer,
            $this->coordinatesSerializer,
            $this->placeSerializer
        );
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldNotSerializeWithIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->serialize($this->getInvalidObject());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->givenUserSerializerWillSerializeUser();
        $this->givenEntitiesSerializerWillSerializeEntities();
        $this->givenCoordinatesSerializerWillSerializeCoordinates();
        $this->givenPlaceSerializerWillSerializePlace();

        $serialized = $this->serializer->serialize($this->getValidObject());

        $this->assertEquals($this->id, $serialized->id);
        $this->assertEquals($this->text, $serialized->text);
        $this->assertEquals($this->userObj, $serialized->user);
        $this->assertEquals($this->lang, $serialized->lang);
        $this->assertEquals($this->date->format(TwitterDate::FORMAT), $serialized->created_at);
        $this->assertEquals($this->entitiesObj, $serialized->entities);
        $this->assertEquals($this->coordinatesObj, $serialized->coordinates);
        $this->assertEquals($this->placeObj, $serialized->place);
        $this->assertEquals($this->replyStatusId, $serialized->in_reply_to_status_id);
        $this->assertEquals($this->replyUserId, $serialized->in_reply_to_user_id);
        $this->assertEquals($this->replyUserScreenName, $serialized->in_reply_to_screen_name);
        $this->assertEquals($this->retweeted, $serialized->retweeted);
        $this->assertEquals($this->retweetCount, $serialized->retweet_count);
        $this->assertEquals($this->favorited, $serialized->favorited);
        $this->assertEquals($this->favoriteCount, $serialized->favorite_count);
        $this->assertEquals($this->truncated, $serialized->truncated);
        $this->assertEquals($this->source, $serialized->source);
        $this->assertNotNull($serialized->retweeted_status);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->givenUserSerializerWillUnserializeUser();
        $this->givenEntitiesSerializerWillUnserializeEntities();
        $this->givenCoordinatesSerializerWillUnserializeCoordinates();
        $this->givenPlaceSerializerWillUnserializePlace();

        $tweet = $this->serializer->unserialize($this->getValidSerializedObject());

        $this->assertEquals($this->id, $tweet->getId());
        $this->assertEquals($this->text, $tweet->getText());
        $this->assertEquals($this->user, $tweet->getSender());
        $this->assertEquals($this->lang, $tweet->getLang());
        $this->assertEquals($this->date, $tweet->getDate());
        $this->assertEquals($this->entities, $tweet->getEntities());
        $this->assertEquals($this->coordinates, $tweet->getCoordinates());
        $this->assertEquals($this->place, $tweet->getPlace());
        $this->assertEquals($this->replyStatusId, $tweet->getInReplyToStatusId());
        $this->assertEquals($this->replyUserId, $tweet->getInReplyToUserId());
        $this->assertEquals($this->replyUserScreenName, $tweet->getInReplyToScreenName());
        $this->assertEquals($this->retweeted, $tweet->isRetweeted());
        $this->assertEquals($this->retweetCount, $tweet->getRetweetCount());
        $this->assertEquals($this->favorited, $tweet->isFavorited());
        $this->assertEquals($this->favoriteCount, $tweet->getFavoriteCount());
        $this->assertEquals($this->truncated, $tweet->isTruncated());
        $this->assertEquals($this->source, $tweet->getSource());
        $this->assertTrue($tweet->getRetweetedStatus() instanceof Tweet);
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($this->getInvalidSerializedObject());
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TweetSerializer::build();

        $this->assertInstanceOf(TweetSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getInvalidObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @return Tweet
     */
    private function getValidObject()
    {
        $retweet = Tweet::create(
            $this->retweetId,
            $this->user,
            $this->retweetText,
            $this->lang,
            $this->date,
            $this->entities
        );

        $tweet = Tweet::create(
            $this->id,
            $this->user,
            $this->text,
            $this->lang,
            $this->date,
            $this->entities,
            $this->coordinates,
            $this->place,
            $this->replyStatusId,
            $this->replyUserId,
            $this->replyUserScreenName,
            $this->retweeted,
            $this->retweetCount,
            $this->favorited,
            $this->favoriteCount,
            $this->truncated,
            $this->source,
            $retweet
        );
        return $tweet;
    }

    /**
     * @return \stdClass
     */
    private function getValidSerializedObject()
    {
        $tweetObj = new \stdClass();
        $tweetObj->id = (string)$this->id;
        $tweetObj->user = $this->userObj;
        $tweetObj->text = $this->text;
        $tweetObj->lang = $this->lang;
        $tweetObj->created_at = $this->date->format(TwitterDate::FORMAT);
        $tweetObj->entities = $this->entitiesObj;
        $tweetObj->coordinates = $this->coordinatesObj;
        $tweetObj->place = $this->placeObj;
        $tweetObj->in_reply_to_status_id = $this->replyStatusId;
        $tweetObj->in_reply_to_user_id = $this->replyUserId;
        $tweetObj->in_reply_to_screen_name = $this->replyUserScreenName;
        $tweetObj->retweeted = $this->retweeted;
        $tweetObj->retweet_count = $this->retweetCount;
        $tweetObj->favorited = $this->favorited;
        $tweetObj->favorite_count = $this->favoriteCount;
        $tweetObj->truncated = $this->truncated;
        $tweetObj->source = $this->source;
        $tweetObj->retweeted_status = clone $tweetObj;
        return $tweetObj;
    }

    /**
     * @return \stdClass
     */
    private function getInvalidSerializedObject()
    {
        return new \stdClass();
    }

    private function givenUserSerializerWillSerializeUser()
    {
        $this->userSerializer
            ->shouldReceive('serialize')
            ->with($this->user)
            ->andReturn($this->userObj);
    }

    private function givenEntitiesSerializerWillSerializeEntities()
    {
        $this->entitiesSerializer
            ->shouldReceive('serialize')
            ->with($this->entities)
            ->andReturn($this->entitiesObj);
    }

    private function givenCoordinatesSerializerWillSerializeCoordinates()
    {
        $this->coordinatesSerializer
            ->shouldReceive('serialize')
            ->with($this->coordinates)
            ->andReturn($this->coordinatesObj);
    }

    private function givenPlaceSerializerWillSerializePlace()
    {
        $this->placeSerializer
            ->shouldReceive('serialize')
            ->with($this->place)
            ->andReturn($this->placeObj);
    }

    private function givenUserSerializerWillUnserializeUser()
    {
        $this->userSerializer
            ->shouldReceive('unserialize')
            ->with($this->userObj)
            ->andReturn($this->user);
    }

    private function givenEntitiesSerializerWillUnserializeEntities()
    {
        $this->entitiesSerializer
            ->shouldReceive('unserialize')
            ->with($this->entitiesObj)
            ->andReturn($this->entities);
    }

    private function givenCoordinatesSerializerWillUnserializeCoordinates()
    {
        $this->coordinatesSerializer
            ->shouldReceive('unserialize')
            ->with($this->coordinatesObj)
            ->andReturn($this->coordinates);
    }

    private function givenPlaceSerializerWillUnserializePlace()
    {
        $this->placeSerializer
            ->shouldReceive('unserialize')
            ->with($this->placeObj)
            ->andReturn($this->place);
    }
}
