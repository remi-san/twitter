<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterVariantMedia;

class VariantMediaTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testConstructor()
    {
        $contentType = 'media/mpeg';
        $url = 'http://med.ia/video.mpg';
        $bitrate = 12.4;

        $variantMedia = new TwitterVariantMedia($contentType, $url, $bitrate);

        $this->assertEquals($contentType, $variantMedia->getContentType());
        $this->assertEquals($url, $variantMedia->getUrl());
        $this->assertEquals($bitrate, $variantMedia->getBitrate());
    }
} 