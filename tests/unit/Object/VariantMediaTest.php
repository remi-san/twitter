<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterVariantMedia;

class VariantMediaTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $contentType;

    /** @var string */
    private $url;

    /** @var float */
    private $bitrate;

    public function setUp()
    {
        $faker = Factory::create();

        $this->contentType = $faker->mimeType;
        $this->url = $faker->url;
        $this->bitrate = $faker->randomFloat();
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
        $variantMedia = TwitterVariantMedia::create(
            $this->contentType,
            $this->url,
            $this->bitrate
        );

        $this->assertEquals($this->contentType, $variantMedia->getContentType());
        $this->assertEquals($this->url, $variantMedia->getUrl());
        $this->assertEquals($this->bitrate, $variantMedia->getBitrate());
    }
}
