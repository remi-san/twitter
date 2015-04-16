<?php
namespace Twitter\Test\Serializer;

use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Test\Mock\TwitterObjectMocker;
use Twitter\Test\Mock\TwitterSerializerMocker;

class DirectMessageSerializerTest extends \PHPUnit_Framework_TestCase {
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
        $obj = $this->getDirectMessage();

        $this->setExpectedException('\\BadMethodCallException');

        $this->serializer->serialize($obj);
    }

    /**
     * @test
     */
    public function testUnserialize()
    {
        $senderObj = new \stdClass(); $senderObj->type = 'sender';
        $sender = $this->getTwitterUser(33, 'doc');
        $this->userSerializer->shouldReceive('unserialize')->with($senderObj)->andReturn($sender);

        $recipientObj = new \stdClass(); $recipientObj->type = 'recipient';
        $recipient = $this->getTwitterUser(42, 'douglas');
        $this->userSerializer->shouldReceive('unserialize')->with($recipientObj)->andReturn($recipient);

        $entitiesObj = new \stdClass(); $entitiesObj->type = 'entities';
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

        $this->assertEquals($dmObj->id, $dm->getId());
        $this->assertEquals($sender, $dm->getSender());
        $this->assertEquals($recipient, $dm->getRecipient());
        $this->assertEquals($dmObj->text, $dm->getText());
        $this->assertEquals(new \DateTime($dmObj->created_at), $dm->getDate());
        $this->assertEquals($entities, $dm->getEntities());
    }
} 