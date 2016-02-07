<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterEvent;
use Twitter\Test\Mock\TwitterObjectMocker;

class EventTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $type = TwitterEvent::ACCESS_REVOKED;
        $source = $this->getTwitterUser(42, 'douglas');
        $target = $this->getTwitterUser(314, 'pi');
        $object = $this->getTwitterEventTarget();
        $object->shouldReceive('__toString')->andReturn('target');
        $date = new \DateTime();

        $event = TwitterEvent::create($type, $source, $target, $object, $date);

        $this->assertEquals($type, $event->getType());
        $this->assertEquals($source, $event->getSource());
        $this->assertEquals($target, $event->getTarget());
        $this->assertEquals($object, $event->getObject());
        $this->assertEquals($date, $event->getDate());
        $this->assertEquals('Event ['.$type.']: target', $event->__toString());
    }
}
