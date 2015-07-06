<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\Tweet;
use Twitter\Serializer\TweetSerializer;

use Twitter\Serializer\TwitterCoordinatesSerializer;
use Twitter\Serializer\TwitterEntitiesSerializer;


use Twitter\Serializer\TwitterPlaceSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class TweetSerializerTest extends \PHPUnit_Framework_TestCase {
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
    public function testSerializeWithIllegalObject()
    {
        $user = $this->getTwitterUser(42, 'douglas');

        $this->setExpectedException('\\InvalidArgumentException');

        $this->serializer->serialize($user);
    }

    /**
     * @test
     */
    public function testSerializeWithLegalObject()
    {
        $tweet = $this->getTweet();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($tweet);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $userObj = new \stdClass(); $userObj->type = 'user';
        $user = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('unserialize')->with($userObj)->andReturn($user);

        $entitiesObj = new \stdClass(); $entitiesObj->type = 'entities';
        $entities = $this->getTwitterEntities();
        $this->entitiesSerializer->shouldReceive('unserialize')->with($entitiesObj)->andReturn($entities);

        $coordinatesObj = new \stdClass(); $coordinatesObj->type = 'coordinates';
        $coordinates = $this->getCoordinates();
        $this->coordinatesSerializer->shouldReceive('unserialize')->with($coordinatesObj)->andReturn($coordinates);

        $placeObj = new \stdClass(); $placeObj->type = 'place';
        $place = $this->getPlace();
        $this->placeSerializer->shouldReceive('unserialize')->with($placeObj)->andReturn($place);

        $tweetObj = new \stdClass();
        $tweetObj->id = 42;
        $tweetObj->user = $userObj;
        $tweetObj->text = 'my tweet';
        $tweetObj->lang = 'fr';
        $tweetObj->created_at = '2015-01-01 12:00:00';
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

        $this->assertEquals($tweetObj->id, $tweet->getId());
        $this->assertEquals($tweetObj->text, $tweet->getText());
        $this->assertEquals($user, $tweet->getSender());
        $this->assertEquals($tweetObj->lang, $tweet->getLang());
        $this->assertEquals(new \DateTime($tweetObj->created_at), $tweet->getDate());
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
} 