<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterUrl;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $url;

    /** @var string */
    private $displayUrl;

    /** @var string */
    private $expandedUrl;

    /** @var TwitterEntityIndices */
    private $indices;

    public function setUp()
    {
        $faker = Factory::create();

        $this->url = $faker->url;
        $this->displayUrl = $faker->url;
        $this->expandedUrl = $faker->url;
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);
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
        $urlObject = TwitterUrl::create($this->url, $this->displayUrl, $this->expandedUrl, $this->indices);

        $this->assertEquals($this->url, $urlObject->getUrl());
        $this->assertEquals($this->displayUrl, $urlObject->getDisplayUrl());
        $this->assertEquals($this->expandedUrl, $urlObject->getExpandedUrl());
        $this->assertEquals($this->indices, $urlObject->getIndices());
    }
}
