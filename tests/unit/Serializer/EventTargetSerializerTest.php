<?php
namespace Twitter\Test\Serializer;

use Twitter\Object\Tweet;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterMessageId;

class EventTargetSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterEventTargetSerializer
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
        $this->tweetSerializer->shouldReceive('canSerialize')->with($user)->andReturn(false);

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
        $this->tweetSerializer->shouldReceive('canSerialize')->with($tweet)->andReturn(true);

        $this->serializer->serialize($tweet);
    }

    /**
     * @test
     */
    public function testSerializeWithLegalObjectNotImplemented()
    {
        $this->setExpectedException('\\BadMethodCallException');

        $obj = \Mockery::mock('\\Twitter\\TwitterEventTarget');
        $this->tweetSerializer->shouldReceive('canSerialize')->with($obj)->andReturn(true);

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

        $tweet = Tweet::create(
            TwitterMessageId::create(1),
            TwitterUser::create(),
            'text',
            'fr',
            new \DateTimeImmutable(),
            \Mockery::mock(TwitterEntities::class)
        );

        $this->tweetSerializer->shouldReceive('unserialize')->with($tweetObj)->andReturn($tweet);
        $this->tweetSerializer->shouldReceive('canUnserialize')->with($tweetObj)->andReturn(true);

        $eventTarget = $this->serializer->unserialize($tweetObj);

        $this->assertEquals($tweet, $eventTarget);
    }

    /**
     * @test
     */
    public function testUnserializeNull()
    {
        $obj = new \stdClass();

        $this->tweetSerializer->shouldReceive('canUnserialize')->with($obj)->andReturn(false);

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
