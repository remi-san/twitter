<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Mockery\Mock;
use Twitter\Object\TwitterEvent;
use Twitter\Object\TwitterUser;
use Twitter\TwitterEventTarget;

class EventTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $type;

    /** @var TwitterUser */
    private $source;

    /** @var TwitterUser */
    private $target;

    /** @var TwitterEventTarget | Mock */
    private $object;

    /** @var \DateTimeInterface */
    private $date;

    /** @var string */
    private $stringifiedTargetObject;

    public function setUp()
    {
        $faker = Factory::create();

        $this->type = TwitterEvent::ACCESS_REVOKED;
        $this->source = \Mockery::mock(TwitterUser::class);
        $this->target = \Mockery::mock(TwitterUser::class);
        $this->object = \Mockery::mock(TwitterEventTarget::class);
        $this->date = new \DateTimeImmutable();

        $this->stringifiedTargetObject = $faker->word;
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $event = TwitterEvent::create(
            $this->type,
            $this->source,
            $this->target,
            $this->object,
            $this->date
        );

        $this->targetObjectWillStringify();

        $this->assertEquals($this->type, $event->getType());
        $this->assertEquals($this->source, $event->getSource());
        $this->assertEquals($this->target, $event->getTarget());
        $this->assertEquals($this->object, $event->getObject());
        $this->assertEquals($this->date, $event->getDate());
        $this->assertEquals('Event ['.$this->type.']: '.$this->stringifiedTargetObject, (string) $event);
    }

    private function targetObjectWillStringify()
    {
        $this->object->shouldReceive('__toString')->andReturn($this->stringifiedTargetObject);
    }
}
