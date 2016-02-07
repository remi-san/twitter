<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterMedia;
use Twitter\Test\Mock\TwitterObjectMocker;

class MediaTest extends \PHPUnit_Framework_TestCase
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
        $mediaUrl = 'http://media.url';
        $mediaUrlHttps = 'https://media.url';
        $url = 'http://my.url';
        $displayUrl = 'http://display.url';
        $expandedUrl = 'http://expanded.url';
        $sizes = array($this->getTwitterMediaSize());
        $type = 'type';
        $indices = $this->getTwitterEntityIndices();

        $media = TwitterMedia::create(
            $id,
            $mediaUrl,
            $mediaUrlHttps,
            $url,
            $displayUrl,
            $expandedUrl,
            $sizes,
            $type,
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
    }
}
