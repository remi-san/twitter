<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterUserMention;

class UserMentionTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var string */
    private $screenName;

    /** @var string */
    private $name;

    /** @var TwitterEntityIndices */
    private $indices;

    public function setUp()
    {
        $faker = Factory::create();

        $this->id = $faker->randomNumber();
        $this->screenName = $faker->userName;
        $this->name = $faker->name;
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldBuildTheObject()
    {
        $userMention = TwitterUserMention::create($this->id, $this->screenName, $this->name, $this->indices);

        $this->assertEquals($this->id, $userMention->getId());
        $this->assertEquals($this->screenName, $userMention->getScreenName());
        $this->assertEquals($this->name, $userMention->getName());
        $this->assertEquals($this->indices, $userMention->getIndices());
        $this->assertEquals('@'.$this->screenName, (string) $userMention);
    }
}
