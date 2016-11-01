<?php
namespace Twitter\Test\Serializer;

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
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterMessageId;

class TweetSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TweetSerializer
     */
    private $serializer;

    /**
     * @var TwitterUserSerializer
     */
    private $userSerializer;

    /**
     * @var TwitterEntitiesSerializer
     */
    private $entitiesSerializer;

    /**
     * @var TwitterCoordinatesSerializer
     */
    private $coordinatesSerializer;

    /**
     * @var TwitterPlaceSerializer
     */
    private $placeSerializer;

    public function setUp()
    {
        $this->userSerializer        = $this->getUserSerializer();
        $this->entitiesSerializer    = $this->getEntitiesSerializer();
        $this->coordinatesSerializer = $this->getCoordinatesSerializer();
        $this->placeSerializer       = $this->getPlaceSerializer();

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
        $user = $this->getTwitterUser(42, 'douglas');

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($user);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $id = 666;
        $text = 'text';
        $lang = 'en';
        $date = new \DateTimeImmutable('2010-01-01 12:00:00 +00:00');
        $replyStatusId = 12;
        $replyUserId = 2048;
        $replyUserScreenName = 'gc';
        $retweetCount = 2;
        $favoriteCount = 5;
        $source = 'twitter';

        $userObj = new \stdClass();
        $userObj->type = 'user';
        $user = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('serialize')->with($user)->andReturn($userObj)->twice();

        $entitiesObj = new \stdClass();
        $entitiesObj->type = 'entities';
        $entities = $this->getTwitterEntities();
        $this->entitiesSerializer->shouldReceive('serialize')->with($entities)->andReturn($entitiesObj);

        $coordinatesObj = new \stdClass();
        $coordinatesObj->type = 'coordinates';
        $coordinates = $this->getCoordinates();
        $this->coordinatesSerializer->shouldReceive('serialize')->with($coordinates)->andReturn($coordinatesObj);

        $placeObj = new \stdClass();
        $placeObj->type = 'place';
        $place = $this->getPlace();
        $this->placeSerializer->shouldReceive('serialize')->with($place)->andReturn($placeObj);

        $retweet = $this->buildTweet(99, 'original', $user, null, 'en', new \DateTimeImmutable());

        $tweet = $this->getTweet(TwitterMessageId::create($id), $text, $user, $entities);
        $tweet->shouldReceive('getLang')->andReturn($lang);
        $tweet->shouldReceive('getDate')->andReturn($date);
        $tweet->shouldReceive('getCoordinates')->andReturn($coordinates);
        $tweet->shouldReceive('getPlace')->andReturn($place);
        $tweet->shouldReceive('getInReplyToStatusId')->andReturn($replyStatusId);
        $tweet->shouldReceive('getInReplyToUserId')->andReturn($replyUserId);
        $tweet->shouldReceive('getInReplyToScreenName')->andReturn($replyUserScreenName);
        $tweet->shouldReceive('isRetweeted')->andReturn(true);
        $tweet->shouldReceive('getRetweetCount')->andReturn($retweetCount);
        $tweet->shouldReceive('isFavorited')->andReturn(true);
        $tweet->shouldReceive('getFavoriteCount')->andReturn($favoriteCount);
        $tweet->shouldReceive('isTruncated')->andReturn(false);
        $tweet->shouldReceive('getSource')->andReturn($source);
        $tweet->shouldReceive('getRetweetedStatus')->andReturn($retweet);

        $serialized = $this->serializer->serialize($tweet);
        $this->assertEquals($id, $serialized->id);
        $this->assertEquals($text, $serialized->text);
        $this->assertEquals($userObj, $serialized->user);
        $this->assertEquals($lang, $serialized->lang);
        $this->assertEquals(
            $date->setTimezone(new \DateTimeZone('UTC'))->format(TwitterDate::FORMAT),
            $serialized->created_at
        );
        $this->assertEquals($entitiesObj, $serialized->entities);
        $this->assertEquals($coordinatesObj, $serialized->coordinates);
        $this->assertEquals($placeObj, $serialized->place);
        $this->assertEquals($replyStatusId, $serialized->in_reply_to_status_id);
        $this->assertEquals($replyUserId, $serialized->in_reply_to_user_id);
        $this->assertEquals($replyUserScreenName, $serialized->in_reply_to_screen_name);
        $this->assertTrue($serialized->retweeted);
        $this->assertEquals($retweetCount, $serialized->retweet_count);
        $this->assertTrue($serialized->favorited);
        $this->assertEquals($favoriteCount, $serialized->favorite_count);
        $this->assertFalse($serialized->truncated);
        $this->assertEquals($source, $serialized->source);
        $this->assertNotNull($serialized->retweeted_status);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $userObj = new \stdClass();
        $userObj->type = 'user';
        $user = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('unserialize')->with($userObj)->andReturn($user);

        $entitiesObj = new \stdClass();
        $entitiesObj->type = 'entities';
        $entities = $this->getTwitterEntities();
        $this->entitiesSerializer->shouldReceive('unserialize')->with($entitiesObj)->andReturn($entities);

        $coordinatesObj = new \stdClass();
        $coordinatesObj->type = 'coordinates';
        $coordinates = $this->getCoordinates();
        $this->coordinatesSerializer->shouldReceive('unserialize')->with($coordinatesObj)->andReturn($coordinates);

        $placeObj = new \stdClass();
        $placeObj->type = 'place';
        $place = $this->getPlace();
        $this->placeSerializer->shouldReceive('unserialize')->with($placeObj)->andReturn($place);

        $tweetObj = new \stdClass();
        $tweetObj->id = 42;
        $tweetObj->user = $userObj;
        $tweetObj->text = 'my tweet';
        $tweetObj->lang = 'fr';
        $tweetObj->created_at = (new \DateTimeImmutable('2015-01-01', new \DateTimeZone('UTC')))
            ->format(TwitterDate::FORMAT);
        $tweetObj->entities = $entitiesObj;
        $tweetObj->coordinates = $coordinatesObj;
        $tweetObj->place = $placeObj;
        $tweetObj->in_reply_to_status_id = 23;
        $tweetObj->in_reply_to_user_id = 666;
        $tweetObj->in_reply_to_screen_name = 'satan';
        $tweetObj->retweeted = true;
        $tweetObj->retweet_count = 12;
        $tweetObj->favorited = true;
        $tweetObj->favorite_count = 3;
        $tweetObj->truncated = false;
        $tweetObj->source = 'http://www.sour.ce';

        $retweetObj = clone $tweetObj;
        $tweetObj->retweeted_status = $retweetObj;

        $tweet = $this->serializer->unserialize($tweetObj);

        $this->assertEquals((string) $tweetObj->id, (string) $tweet->getId());
        $this->assertEquals($tweetObj->text, $tweet->getText());
        $this->assertEquals($user, $tweet->getSender());
        $this->assertEquals($tweetObj->lang, $tweet->getLang());
        $this->assertEquals(new \DateTimeImmutable($tweetObj->created_at), $tweet->getDate());
        $this->assertEquals($entities, $tweet->getEntities());
        $this->assertEquals($coordinates, $tweet->getCoordinates());
        $this->assertEquals($place, $tweet->getPlace());
        $this->assertEquals($tweetObj->in_reply_to_status_id, $tweet->getInReplyToStatusId());
        $this->assertEquals($tweetObj->in_reply_to_user_id, $tweet->getInReplyToUserId());
        $this->assertEquals($tweetObj->in_reply_to_screen_name, $tweet->getInReplyToScreenName());
        $this->assertEquals($tweetObj->retweeted, $tweet->isRetweeted());
        $this->assertEquals($tweetObj->retweet_count, $tweet->getRetweetCount());
        $this->assertEquals($tweetObj->favorited, $tweet->isFavorited());
        $this->assertEquals($tweetObj->favorite_count, $tweet->getFavoriteCount());
        $this->assertEquals($tweetObj->truncated, $tweet->isTruncated());
        $this->assertEquals($tweetObj->source, $tweet->getSource());
        $this->assertTrue($tweet->getRetweetedStatus() instanceof Tweet);
    }

    private function buildTweet(
        $id = null,
        $text = null,
        TwitterUser $sender = null,
        TwitterEntities $entities = null,
        $lang = null,
        \DateTimeInterface $createdAt = null,
        TwitterCoordinates $coordinates = null,
        TwitterPlace $place = null,
        $inReplyToStatusId = null,
        $inReplyToUserId = null,
        $inReplyToScreenName = null,
        $retweeted = false,
        $retweetCount = 0,
        $favorited = false,
        $favoriteCount = 0,
        $truncated = false,
        $source = null,
        Tweet $retweetedStatus = null
    ) {
        $tweet = $this->getTweet(TwitterMessageId::create($id), $text, $sender, $entities);
        $tweet->shouldReceive('getLang')->andReturn($lang);
        $tweet->shouldReceive('getDate')->andReturn($createdAt);
        $tweet->shouldReceive('getCoordinates')->andReturn($coordinates);
        $tweet->shouldReceive('getPlace')->andReturn($place);
        $tweet->shouldReceive('getInReplyToStatusId')->andReturn($inReplyToStatusId);
        $tweet->shouldReceive('getInReplyToUserId')->andReturn($inReplyToUserId);
        $tweet->shouldReceive('getInReplyToScreenName')->andReturn($inReplyToScreenName);
        $tweet->shouldReceive('isRetweeted')->andReturn($retweeted);
        $tweet->shouldReceive('getRetweetCount')->andReturn($retweetCount);
        $tweet->shouldReceive('isFavorited')->andReturn($favorited);
        $tweet->shouldReceive('getFavoriteCount')->andReturn($favoriteCount);
        $tweet->shouldReceive('isTruncated')->andReturn($truncated);
        $tweet->shouldReceive('getSource')->andReturn($source);
        $tweet->shouldReceive('getRetweetedStatus')->andReturn($retweetedStatus);

        return $tweet;
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TweetSerializer::build();

        $this->assertInstanceOf(TweetSerializer::class, $serializer);
    }
}
