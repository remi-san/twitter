<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;
use Twitter\TwitterMessageId;

class DirectMessageSerializerTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker, TwitterSerializerMocker;

    /**
     * @var TwitterDirectMessageSerializer
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

    public function setUp()
    {
        $this->userSerializer = $this->getUserSerializer();
        $this->entitiesSerializer = $this->getEntitiesSerializer();
        $this->serializer = new TwitterDirectMessageSerializer(
            $this->userSerializer,
            $this->entitiesSerializer
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
        $id = 666;
        $text = 'dm';
        $date = new \DateTime();

        $senderObj = new \stdClass();
        $senderObj->type = 'sender';
        $sender = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('serialize')->with($sender)->andReturn($senderObj);

        $recipientObj = new \stdClass();
        $recipientObj->type = 'recipient';
        $recipient = $this->getTwitterUser(42, 'douglas');
        $this->userSerializer->shouldReceive('serialize')->with($recipient)->andReturn($recipientObj);

        $entitiesObj = new \stdClass();
        $entitiesObj->type = 'entities';
        $entities = $this->getTwitterEntities();
        $this->entitiesSerializer->shouldReceive('serialize')->with($entities)->andReturn($entitiesObj);

        $obj = $this->getDirectMessage(new TwitterMessageId($id), $text, $sender, $entities);
        $obj->shouldReceive('getRecipient')->andReturn($recipient);
        $obj->shouldReceive('getDate')->andReturn($date);

        $serialized = $this->serializer->serialize($obj);

        $this->assertEquals($id, $serialized->id);
        $this->assertEquals($senderObj, $serialized->sender);
        $this->assertEquals($recipientObj, $serialized->recipient);
        $this->assertEquals($text, $serialized->text);
        $this->assertEquals($date, new \DateTime($serialized->created_at));
        $this->assertEquals($entitiesObj, $serialized->entities);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $senderObj = new \stdClass();
        $senderObj->type = 'sender';
        $sender = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('unserialize')->with($senderObj)->andReturn($sender);

        $recipientObj = new \stdClass();
        $recipientObj->type = 'recipient';
        $recipient = $this->getTwitterUser(42, 'douglas');
        $this->userSerializer->shouldReceive('unserialize')->with($recipientObj)->andReturn($recipient);

        $entitiesObj = new \stdClass();
        $entitiesObj->type = 'entities';
        $entities = $this->getTwitterEntities();
        $this->entitiesSerializer->shouldReceive('unserialize')->with($entitiesObj)->andReturn($entities);

        $dmObj = new \stdClass();
        $dmObj->id = 42;
        $dmObj->sender = $senderObj;
        $dmObj->recipient = $recipientObj;
        $dmObj->text = 'direct message';
        $dmObj->created_at = '2015-01-01 12:00:00';
        $dmObj->entities = $entitiesObj;

        $dm = $this->serializer->unserialize($dmObj);

        $this->assertEquals((string) $dmObj->id, (string) $dm->getId());
        $this->assertEquals($sender, $dm->getSender());
        $this->assertEquals($recipient, $dm->getRecipient());
        $this->assertEquals($dmObj->text, $dm->getText());
        $this->assertEquals(new \DateTime($dmObj->created_at), $dm->getDate());
        $this->assertEquals($entities, $dm->getEntities());
    }

    /**
     * @test
     */
    public function testStaticBuilder()
    {
        $serializer = TwitterDirectMessageSerializer::build();

        $this->assertInstanceOf(TwitterDirectMessageSerializer::class, $serializer);
    }
}
