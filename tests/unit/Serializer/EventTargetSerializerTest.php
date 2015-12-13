<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterCoordinatesSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class EventTargetSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterCoordinatesSerializer
     */
    private $serializer;

    /**
     * @var TweetSerializer
     */
    private $tweetSerializer;

    public function setUp()
    {
        $this->tweetSerializer = $this->getTweetSerializer();
        $this->serializer = new TwitterEventTargetSerializer($this->tweetSerializer);
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
        $tweetObj = new \stdClass();
        $tweetObj->id = 42;
        $tweetObj->user = new \stdClass();
        $tweetObj->text = 'my tweet';

        $tweet = $this->getTweet();

        $this->tweetSerializer->shouldReceive('serialize')->with($tweet)->andReturn($tweetObj)->once();

        $this->serializer->serialize($tweet);
    }

    /**
     * @test
     */
    public function testSerializeWithLegalObjectNotImplemented()
    {
        $this->setExpectedException('\\BadMethodCallException');

        $obj = \Mockery::mock('\\Twitter\\TwitterEventTarget');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $tweetObj = new \stdClass();
        $tweetObj->id = 42;
        $tweetObj->user = new \stdClass();
        $tweetObj->text = 'my tweet';

        $tweet = new Tweet(1, new TwitterUser(), 'text', 'fr', new \DateTime());

        $this->tweetSerializer->shouldReceive('unserialize')->with($tweetObj)->andReturn($tweet);

        $eventTarget = $this->serializer->unserialize($tweetObj);

        $this->assertEquals($tweet, $eventTarget);
    }

    /**
     * @test
     */
    public function testUnserializeNull()
    {
        $obj = new \stdClass();

        $eventTarget = $this->serializer->unserialize($obj);

        $this->assertNull($eventTarget);
    }

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterEventTargetSerializer::build();

        $this->assertInstanceOf(TwitterEventTargetSerializer::class, $serializer);
    }
}
