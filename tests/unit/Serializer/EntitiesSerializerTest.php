<?php
namespace Twitter\Test\Serializer;

use Mockery\Mock;
use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterExtendedEntity;
use Twitter\Object\TwitterHashtag;
use Twitter\Object\TwitterMedia;
use Twitter\Object\TwitterSymbol;
use Twitter\Object\TwitterUrl;
use Twitter\Object\TwitterUserMention;
use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterExtendedEntitySerializer;
use Twitter\Serializer\TwitterHashtagSerializer;
use Twitter\Serializer\TwitterMediaSerializer;
use Twitter\Serializer\TwitterSymbolSerializer;
use Twitter\Serializer\TwitterUrlSerializer;
use Twitter\Serializer\TwitterUserMentionSerializer;
use Twitter\TwitterSerializable;

class EntitiesSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var object */
    private $serializedHashtag;

    /** @var object */
    private $serializedSymbol;

    /** @var object */
    private $serializedUrl;

    /** @var object */
    private $serializedUserMention;

    /** @var object */
    private $serializedMedium;

    /** @var object */
    private $serializedExtendedEntity;


    /** @var TwitterHashtag */
    private $hashtag;

    /** @var TwitterSymbol */
    private $symbol;

    /** @var TwitterUrl */
    private $url;

    /** @var TwitterUserMention */
    private $userMention;

    /** @var TwitterMedia */
    private $medium;

    /** @var TwitterExtendedEntity */
    private $extendedEntity;


    /** @var TwitterEntities */
    private $twitterEntities;

    /** @var object */
    private $serializedEntities;


    /** @var TwitterExtendedEntitySerializer | Mock */
    private $extendedEntitySerializer;

    /** @var TwitterHashtagSerializer | Mock */
    private $hashtagSerializer;

    /** @var TwitterMediaSerializer | Mock */
    private $mediaSerializer;

    /** @var TwitterSymbolSerializer | Mock */
    private $symbolSerializer;

    /** @var TwitterUrlSerializer | Mock */
    private $urlSerializer;

    /** @var TwitterUserMentionSerializer | Mock */
    private $userMentionSerializer;

    /** @var TwitterEntitiesSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $this->serializedHashtag = new \stdClass();
        $this->serializedSymbol = new \stdClass();
        $this->serializedUrl = new \stdClass();
        $this->serializedUserMention = new \stdClass();
        $this->serializedMedium = new \stdClass();
        $this->serializedExtendedEntity = new \stdClass();

        $this->hashtag = \Mockery::mock(TwitterHashtag::class);
        $this->symbol = \Mockery::mock(TwitterSymbol::class);
        $this->url = \Mockery::mock(TwitterUrl::class);
        $this->userMention = \Mockery::mock(TwitterUserMention::class);
        $this->medium = \Mockery::mock(TwitterMedia::class);
        $this->extendedEntity = \Mockery::mock(TwitterExtendedEntity::class);

        $this->extendedEntitySerializer = \Mockery::mock(TwitterExtendedEntitySerializer::class);
        $this->hashtagSerializer = \Mockery::mock(TwitterHashtagSerializer::class);
        $this->mediaSerializer = \Mockery::mock(TwitterMediaSerializer::class);
        $this->symbolSerializer = \Mockery::mock(TwitterSymbolSerializer::class);
        $this->urlSerializer = \Mockery::mock(TwitterUrlSerializer::class);
        $this->userMentionSerializer = \Mockery::mock(TwitterUserMentionSerializer::class);

        $this->twitterEntities = $this->getTwitterEntities();
        $this->serializedEntities = $this->getSerializedEntities();

        $this->serviceUnderTest = new TwitterEntitiesSerializer(
            $this->extendedEntitySerializer,
            $this->hashtagSerializer,
            $this->mediaSerializer,
            $this->symbolSerializer,
            $this->urlSerializer,
            $this->userMentionSerializer
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
        $object = \Mockery::mock(TwitterSerializable::class);

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->serialize($object);
    }

    /**
     * @test
     */
    public function itShouldSerializeWithLegalObject()
    {
        $this->itWillSerializeHashtag();
        $this->itWillSerializeSymbol();
        $this->itWillSerializeUrl();
        $this->itWillSerializeUserMention();
        $this->itWillSerializeMedia();
        $this->itWillSerializeExtendedEntity();

        $serialized = $this->serviceUnderTest->serialize($this->twitterEntities);

        $this->assertEquals([$this->serializedHashtag], $serialized->hashtags);
        $this->assertEquals([$this->serializedSymbol], $serialized->symbols);
        $this->assertEquals([$this->serializedUrl], $serialized->urls);
        $this->assertEquals([$this->serializedUserMention], $serialized->user_mentions);
        $this->assertEquals([$this->serializedMedium], $serialized->media);
        $this->assertEquals([$this->serializedExtendedEntity], $serialized->extended_entities);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $this->itWillUnserializeHashtag();
        $this->itWillUnserializeSymbol();
        $this->itWillUnserializeUrl();
        $this->itWillUnserializeUserMention();
        $this->itWillUnserializeMedia();
        $this->itWillUnserializeExtendedEntity();

        $entity = $this->serviceUnderTest->unserialize($this->serializedEntities);

        $this->assertEquals(array($this->hashtag), $entity->getHashtags());
        $this->assertEquals(array($this->symbol), $entity->getSymbols());
        $this->assertEquals(array($this->url), $entity->getUrls());
        $this->assertEquals(array($this->userMention), $entity->getUserMentions());
        $this->assertEquals(array($this->medium), $entity->getMedia());
        $this->assertEquals(array($this->extendedEntity), $entity->getExtendedEntities());
    }

    /**
     * @test
     */
    public function itShouldNotUnserializeIllegalObject()
    {
        $obj = $this->getIllegalObject();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serviceUnderTest->unserialize($obj);
    }

    /**
     * @test
     */
    public function itShouldBuildUsingStaticBuilder()
    {
        $serializer = TwitterEntitiesSerializer::build();

        $this->assertInstanceOf(TwitterEntitiesSerializer::class, $serializer);
    }

    private function itWillSerializeHashtag()
    {
        $this->hashtagSerializer
            ->shouldReceive('serialize')
            ->with($this->hashtag)
            ->andReturn($this->serializedHashtag);
    }

    private function itWillSerializeSymbol()
    {
        $this->symbolSerializer
            ->shouldReceive('serialize')
            ->with($this->symbol)
            ->andReturn($this->serializedSymbol);
    }

    private function itWillSerializeUrl()
    {
        $this->urlSerializer
            ->shouldReceive('serialize')
            ->with($this->url)
            ->andReturn($this->serializedUrl);
    }

    private function itWillSerializeUserMention()
    {
        $this->userMentionSerializer
            ->shouldReceive('serialize')
            ->with($this->userMention)
            ->andReturn($this->serializedUserMention);
    }

    private function itWillSerializeMedia()
    {
        $this->mediaSerializer
            ->shouldReceive('serialize')
            ->with($this->medium)
            ->andReturn($this->serializedMedium);
    }

    private function itWillSerializeExtendedEntity()
    {
        $this->extendedEntitySerializer
            ->shouldReceive('serialize')
            ->with($this->extendedEntity)
            ->andReturn($this->serializedExtendedEntity);
    }

    private function itWillUnserializeHashtag()
    {
        $this->hashtagSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedHashtag)
            ->andReturn($this->hashtag);
    }

    private function itWillUnserializeSymbol()
    {
        $this->symbolSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedSymbol)
            ->andReturn($this->symbol);
    }

    private function itWillUnserializeUrl()
    {
        $this->urlSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedUrl)
            ->andReturn($this->url);
    }

    private function itWillUnserializeUserMention()
    {
        $this->userMentionSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedUserMention)
            ->andReturn($this->userMention);
    }

    private function itWillUnserializeMedia()
    {
        $this->mediaSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedMedium)
            ->andReturn($this->medium);
    }

    private function itWillUnserializeExtendedEntity()
    {
        $this->extendedEntitySerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedExtendedEntity)
            ->andReturn($this->extendedEntity);
    }

    /**
     * @return \stdClass
     */
    private function getSerializedEntities()
    {
        $serializedEntities = new \stdClass();
        $serializedEntities->hashtags = array($this->serializedHashtag);
        $serializedEntities->symbols = array($this->serializedSymbol);
        $serializedEntities->urls = array($this->serializedUrl);
        $serializedEntities->user_mentions = array($this->serializedUserMention);
        $serializedEntities->media = array($this->serializedMedium);
        $serializedEntities->extended_entities = array($this->serializedExtendedEntity);
        return $serializedEntities;
    }

    /**
     * @return TwitterEntities
     */
    private function getTwitterEntities()
    {
        $twitterEntities = TwitterEntities::create(
            [$this->hashtag],
            [$this->userMention],
            [$this->url],
            [$this->medium],
            [$this->symbol],
            [$this->extendedEntity]
        );
        return $twitterEntities;
    }

    /**
     * @return \stdClass
     */
    private function getIllegalObject()
    {
        return new \stdClass();
    }
}
