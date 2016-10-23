<?php
namespace Twitter\Test\Object;

use Faker\Factory;
use Twitter\Object\TwitterEntityIndices;
use Twitter\Object\TwitterExtendedEntity;
use Twitter\Object\TwitterMediaSize;
use Twitter\Object\TwitterVariantMedia;

class ExtendedEntityTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $id;

    /** @var string */
    private $mediaUrl;

    /** @var string */
    private $mediaUrlHttps;

    /** @var string */
    private $url;

    /** @var string */
    private $displayUrl;

    /** @var string */
    private $expandedUrl;

    /** @var TwitterMediaSize[] */
    private $sizes;

    /** @var string */
    private $type;

    /** @var TwitterEntityIndices */
    private $indices;

    /** @var string */
    private $videoInfo;

    /** @var int */
    private $durationMillis;

    /** @var array */
    private $variants;

    public function setUp()
    {
        $faker = Factory::create();

        $this->id = $faker->randomNumber();
        $this->mediaUrl = $faker->url;
        $this->mediaUrlHttps = $faker->url;
        $this->url = $faker->url;
        $this->displayUrl = $faker->url;
        $this->expandedUrl = $faker->url;
        $this->sizes = [\Mockery::mock(TwitterMediaSize::class)];
        $this->type = $faker->word;
        $this->indices = \Mockery::mock(TwitterEntityIndices::class);
        $this->videoInfo = $faker->word;
        $this->durationMillis = $faker->randomNumber();
        $this->variants = ['variant' => \Mockery::mock(TwitterVariantMedia::class)];
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testGettersSetters()
    {
        $media = TwitterExtendedEntity::create(
            $this->id,
            $this->mediaUrl,
            $this->mediaUrlHttps,
            $this->url,
            $this->displayUrl,
            $this->expandedUrl,
            $this->sizes,
            $this->type,
            $this->videoInfo,
            $this->durationMillis,
            $this->variants,
            $this->indices
        );

        $this->assertEquals($this->id, $media->getId());
        $this->assertEquals($this->mediaUrl, $media->getMediaUrl());
        $this->assertEquals($this->mediaUrlHttps, $media->getMediaUrlHttps());
        $this->assertEquals($this->url, $media->getUrl());
        $this->assertEquals($this->displayUrl, $media->getDisplayUrl());
        $this->assertEquals($this->expandedUrl, $media->getExpandedUrl());
        $this->assertEquals($this->sizes, $media->getSizes());
        $this->assertEquals($this->type, $media->getType());
        $this->assertEquals($this->indices, $media->getIndices());
        $this->assertEquals($this->videoInfo, $media->getVideoInfo());
        $this->assertEquals($this->durationMillis, $media->getDurationMillis());
        $this->assertEquals($this->variants, $media->getVariants());
    }
}
