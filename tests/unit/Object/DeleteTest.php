<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterDelete;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $type = TwitterDelete::DM;
        $id = 42;
        $userId = 314;
        $date = new \DateTime();

        $delete = TwitterDelete::create($type, $id, $userId, $date);

        $this->assertEquals($type, $delete->getType());
        $this->assertEquals($id, $delete->getId());
        $this->assertEquals($userId, $delete->getUserId());
        $this->assertEquals($date, $delete->getDate());
        $this->assertEquals('Delete ['.$type.']['.$id.']', $delete->__toString());
    }
}
