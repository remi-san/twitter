<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterExtendedEntity;
use Twitter\Test\Mock\TwitterObjectMocker;

class ExtendedEntityTest extends \PHPUnit_Framework_TestCase
{
    use TwitterObjectMocker;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testGettersSetters()
    {
        $id = 42;
        $mediaUrl = 'http://media.url';
        $mediaUrlHttps = 'https://media.url';
        $url = 'http://my.url';
        $displayUrl = 'http://display.url';
        $expandedUrl = 'http://expanded.url';
        $sizes = array($this->getTwitterMediaSize());
        $type = 'type';
        $indices = $this->getTwitterEntityIndices();
        $videoInfo = 'info';
        $durationMillis = 1000;
        $variants = array('variant'=>$this->getVariantMedia());

        $media = new TwitterExtendedEntity(
            $id,
            $mediaUrl,
            $mediaUrlHttps,
            $url,
            $displayUrl,
            $expandedUrl,
            $sizes,
            $type,
            $videoInfo,
            $durationMillis,
            $variants,
            $indices
        );

        $this->assertEquals($id, $media->getId());
        $this->assertEquals($mediaUrl, $media->getMediaUrl());
        $this->assertEquals($mediaUrlHttps, $media->getMediaUrlHttps());
        $this->assertEquals($url, $media->getUrl());
        $this->assertEquals($displayUrl, $media->getDisplayUrl());
        $this->assertEquals($expandedUrl, $media->getExpandedUrl());
        $this->assertEquals($sizes, $media->getSizes());
        $this->assertEquals($type, $media->getType());
        $this->assertEquals($indices, $media->getIndices());
        $this->assertEquals($videoInfo, $media->getVideoInfo());
        $this->assertEquals($durationMillis, $media->getDurationMillis());
        $this->assertEquals($variants, $media->getVariants());
    }
}
