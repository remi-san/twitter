<?php
namespace Twitter\Test\Serializer;

use Faker\Factory;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\TwitterSerializable;

class EntityIndicesSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $from;

    /** @var int */
    private $to;

    /** @var TwitterEntityIndicesSerializer */
    private $serviceUnderTest;

    public function setUp()
    {
        $faker = Factory::create();

        $this->from = $faker->numberBetween(0, 50);
        $this->to = $faker->numberBetween(51, 100);

        $this->serviceUnderTest = new TwitterEntityIndicesSerializer();
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
        $entityIndices = TwitterEntityIndices::create($this->from, $this->to);

        $serialized = $this->serviceUnderTest->serialize($entityIndices);

        $this->assertEquals($this->from, $serialized[0]);
        $this->assertEquals($this->to, $serialized[1]);
    }

    /**
     * @test
     */
    public function itShouldUnserialize()
    {
        $serializedIndices = [$this->from, $this->to];

        $indices = $this->serviceUnderTest->unserialize($serializedIndices);

        $this->assertEquals($this->from, $indices->getFrom());
        $this->assertEquals($this->to, $indices->getTo());
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
        $serializer = TwitterEntityIndicesSerializer::build();

        $this->assertInstanceOf(TwitterEntityIndicesSerializer::class, $serializer);
    }

    /**
     * @return array
     */
    private function getIllegalObject()
    {
        return [];
    }
}
