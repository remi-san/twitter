<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\Tweet;
use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\TwitterEventTarget;
use Twitter\TwitterSerializable;

class EventTargetSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TweetSerializer | Mock */
    private $tweetSerializer;

    /** @var TwitterEventTargetSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $this->tweetSerializer = \Mockery::mock(TweetSerializer::class);

        $this->serviceUnderTest = new TwitterEventTargetSerializer($this->tweetSerializer);
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
        $object = $this->getIllegalObject();

        $this->tweetSerializerCanNotSerialize($object);

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $tweetObj = new \stdClass();
        $tweet = \Mockery::mock(Tweet::class);

        $this->tweetSerializerCanSerialize($tweet);

        $this->assertTweetSerializerWillSerializeTweet($tweet, $tweetObj);

        $return = $this->serviceUnderTest->serialize($tweet);

        $this->assertEquals($tweetObj, $return);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObjectNotImplemented()
    {
        $object = \Mockery::mock(TwitterEventTarget::class);

        $this->tweetSerializerCanNotSerialize($object);

        $this->setExpectedException(\BadMethodCallException::class);

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $tweetObj = new \stdClass();
        $tweet = \Mockery::mock(Tweet::class);

        $this->tweetSerializerCanUnserialize($tweetObj);
        $this->assertTweetSerializerWillUnserializeTweet($tweetObj, $tweet);

        $eventTarget = $this->serviceUnderTest->unserialize($tweetObj);

        $this->assertEquals($tweet, $eventTarget);
    }

    /**
     * @test
     */
    public function itShouldUnserializeNull()
    {
        $obj = $this->getNullObject();

        $this->tweetSerializer->shouldReceive('canUnserialize')->with($obj)->andReturn(false);

        $eventTarget = $this->serviceUnderTest->unserialize($obj);

        $this->assertNull($eventTarget);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterEventTargetSerializer::build();

        $this->assertInstanceOf(TwitterEventTargetSerializer::class, $serializer);
    }

    /**
     * @return TwitterSerializable
     */
    private function getIllegalObject()
    {
        return \Mockery::mock(TwitterSerializable::class);
    }

    /**
     * @param $object
     */
    private function tweetSerializerCanNotSerialize($object)
    {
        $this->tweetSerializer->shouldReceive('canSerialize')->with($object)->andReturn(false);
    }

    /**
     * @param $tweet
     */
    private function tweetSerializerCanSerialize($tweet)
    {
        $this->tweetSerializer->shouldReceive('canSerialize')->with($tweet)->andReturn(true);
    }

    /**
     * @param $tweet
     * @param $tweetObj
     */
    private function assertTweetSerializerWillSerializeTweet($tweet, $tweetObj)
    {
        $this->tweetSerializer->shouldReceive('serialize')->with($tweet)->andReturn($tweetObj)->once();
    }

    /**
     * @param $tweetObj
     */
    private function tweetSerializerCanUnserialize($tweetObj)
    {
        $this->tweetSerializer->shouldReceive('canUnserialize')->with($tweetObj)->andReturn(true);
    }

    /**
     * @param $tweetObj
     * @param $tweet
     */
    private function assertTweetSerializerWillUnserializeTweet($tweetObj, $tweet)
    {
        $this->tweetSerializer->shouldReceive('unserialize')->with($tweetObj)->andReturn($tweet)->once();
    }

    /**
     * @return \stdClass
     */
    private function getNullObject()
    {
        return new \stdClass();
    }
}
