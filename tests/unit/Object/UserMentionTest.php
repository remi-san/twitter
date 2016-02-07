<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterUserMention;
use Twitter\Test\Mock\TwitterObjectMocker;

class UserMentionTest extends \PHPUnit_Framework_TestCase
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
        $id = 42;
        $screenName = 'douglas';
        $name = 'Douglas Adams';
        $indices = $this->getIndices();

        $userMention = TwitterUserMention::create($id, $screenName, $name, $indices);

        $this->assertEquals($id, $userMention->getId());
        $this->assertEquals($screenName, $userMention->getScreenName());
        $this->assertEquals($name, $userMention->getName());
        $this->assertEquals($indices, $userMention->getIndices());
        $this->assertEquals('@'.$screenName, $userMention->__toString());
    }
}
