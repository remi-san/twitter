<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterDelete;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $type;

    /** @var int */
    private $id;

    /** @var int */
    private $userId;

    /** @var \DateTimeInterface */
    private $date;

    public function setUp()
    {
        $faker = Factory::create();

        $this->type = TwitterDelete::DM;
        $this->id = $faker->randomNumber();
        $this->userId = $faker->randomNumber();
        $this->date = new \DateTimeImmutable();
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
        $delete = TwitterDelete::create($this->type, $this->id, $this->userId, $this->date);

        $this->assertEquals($this->type, $delete->getType());
        $this->assertEquals($this->id, $delete->getId());
        $this->assertEquals($this->userId, $delete->getUserId());
        $this->assertEquals($this->date, $delete->getDate());
        $this->assertEquals('Deleted ['.$this->type.']['.$this->id.']', (string) $delete);
    }
}
