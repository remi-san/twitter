<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterExtendedEntitySerializer;
use Twitter\Serializer\TwitterHashtagSerializer;
use Twitter\Serializer\TwitterMediaSerializer;
use Twitter\Serializer\TwitterSymbolSerializer;
use Twitter\Serializer\TwitterUrlSerializer;
use Twitter\Serializer\TwitterUserMentionSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class EntitiesSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterEntitiesSerializer
     */
    private $serializer;

    /**
     * @var TwitterExtendedEntitySerializer
     */
    private $extendedEntitySerializer;

    /**
     * @var TwitterHashtagSerializer
     */
    private $hashtagSerializer;

    /**
     * @var TwitterMediaSerializer
     */
    private $mediaSerializer;

    /**
     * @var TwitterSymbolSerializer
     */
    private $symbolSerializer;

    /**
     * @var TwitterUrlSerializer
     */
    private $urlSerializer;

    /**
     * @var TwitterUserMentionSerializer
     */
    private $userMentionSerializer;

    public function setUp()
    {
        $this->extendedEntitySerializer = $this->getExtendedEntitySerializer();
        $this->hashtagSerializer = $this->getHashtagSerializer();
        $this->mediaSerializer = $this->getMediaSerializer();
        $this->symbolSerializer = $this->getSymbolSerializer();
        $this->urlSerializer = $this->getUrlSerializer();
        $this->userMentionSerializer = $this->getUserMentionSerializer();

        $this->serializer = new TwitterEntitiesSerializer(
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
        $hashtagObj = new \stdClass();
        $hashtagObj->type = 'hashtag';
        $hashtag = $this->getHashTag('hashtag');
        $this->hashtagSerializer->shouldReceive('serialize')->with($hashtag)->andReturn($hashtagObj);

        $symbolObj = new \stdClass();
        $symbolObj->type = 'symbol';
        $symbol = $this->getSymbol();
        $this->symbolSerializer->shouldReceive('serialize')->with($symbol)->andReturn($symbolObj);

        $urlObj = new \stdClass();
        $urlObj->type = 'url';
        $url = $this->getUrl();
        $this->urlSerializer->shouldReceive('serialize')->with($url)->andReturn($urlObj);

        $userMentionObj = new \stdClass();
        $userMentionObj->type = 'user mention';
        $userMention = $this->getUserMention();
        $this->userMentionSerializer->shouldReceive('serialize')->with($userMention)->andReturn($userMentionObj);

        $mediumObj = new \stdClass();
        $mediumObj->type = 'medium';
        $medium = $this->getMedia();
        $this->mediaSerializer->shouldReceive('serialize')->with($medium)->andReturn($mediumObj);

        $extendedEntityObj = new \stdClass();
        $extendedEntityObj->type = 'extended entity';
        $extendedEntity = $this->getExtendedEntity();
        $this->extendedEntitySerializer
            ->shouldReceive('serialize')
            ->with($extendedEntity)
            ->andReturn($extendedEntityObj);

        $obj = $this->getTwitterEntities();
        $obj->shouldReceive('getHashtags')->andReturn(array($hashtag));
        $obj->shouldReceive('getSymbols')->andReturn(array($symbol));
        $obj->shouldReceive('getUrls')->andReturn(array($url));
        $obj->shouldReceive('getUserMentions')->andReturn(array($userMention));
        $obj->shouldReceive('getMedia')->andReturn(array($medium));
        $obj->shouldReceive('getExtendedEntities')->andReturn(array($extendedEntity));

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals(array($hashtagObj), $serialized->hashtags);
        $this->assertEquals(array($symbolObj), $serialized->symbols);
        $this->assertEquals(array($urlObj), $serialized->urls);
        $this->assertEquals(array($userMentionObj), $serialized->user_mentions);
        $this->assertEquals(array($mediumObj), $serialized->media);
        $this->assertEquals(array($extendedEntityObj), $serialized->extended_entities);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $hashtagObj = new \stdClass();
        $hashtagObj->type = 'hashtag';
        $hashtag = $this->getHashTag('hashtag');
        $this->hashtagSerializer->shouldReceive('unserialize')->with($hashtagObj)->andReturn($hashtag);

        $symbolObj = new \stdClass();
        $symbolObj->type = 'symbol';
        $symbol = $this->getSymbol();
        $this->symbolSerializer->shouldReceive('unserialize')->with($symbolObj)->andReturn($symbol);

        $urlObj = new \stdClass();
        $urlObj->type = 'url';
        $url = $this->getUrl();
        $this->urlSerializer->shouldReceive('unserialize')->with($urlObj)->andReturn($url);

        $userMentionObj = new \stdClass();
        $userMentionObj->type = 'user mention';
        $userMention = $this->getUserMention();
        $this->userMentionSerializer->shouldReceive('unserialize')->with($userMentionObj)->andReturn($userMention);

        $mediumObj = new \stdClass();
        $mediumObj->type = 'medium';
        $medium = $this->getMedia();
        $this->mediaSerializer->shouldReceive('unserialize')->with($mediumObj)->andReturn($medium);

        $extendedEntityObj = new \stdClass();
        $extendedEntityObj->type = 'extended entity';
        $extendedEntity = $this->getExtendedEntity();
        $this->extendedEntitySerializer
            ->shouldReceive('unserialize')
            ->with($extendedEntityObj)
            ->andReturn($extendedEntity);

        $entityObj = new \stdClass();
        $entityObj->hashtags = array($hashtagObj);
        $entityObj->symbols = array($symbolObj);
        $entityObj->urls = array($urlObj);
        $entityObj->user_mentions = array($userMentionObj);
        $entityObj->media = array($mediumObj);
        $entityObj->extended_entities = array($extendedEntityObj);

        $entity = $this->serializer->unserialize($entityObj);

        $this->assertEquals(array($hashtag), $entity->getHashtags());
        $this->assertEquals(array($symbol), $entity->getSymbols());
        $this->assertEquals(array($url), $entity->getUrls());
        $this->assertEquals(array($userMention), $entity->getUserMentions());
        $this->assertEquals(array($medium), $entity->getMedia());
        $this->assertEquals(array($extendedEntity), $entity->getExtendedEntities());
    }

    /**
     * @test
     */
    public function testIllegalUnserialize()
    {
        $obj = new \stdClass();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->serializer->unserialize($obj);
    }

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterEntitiesSerializer::build();

        $this->assertInstanceOf(TwitterEntitiesSerializer::class, $serializer);
    }
}
