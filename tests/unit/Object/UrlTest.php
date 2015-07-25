<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterUrl;
use Twitter\Test\Mock\TwitterObjectMocker;

class UrlTest extends \PHPUnit_Framework_TestCase
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
        $url = 'http://ur.l';
        $displayUrl = 'http://display.url';
        $expandedUrl = 'http://expanded.url';
        $indices = $this->getIndices();

        $urlObject = new TwitterUrl($url, $displayUrl, $expandedUrl, $indices);

        $this->assertEquals($url, $urlObject->getUrl());
        $this->assertEquals($displayUrl, $urlObject->getDisplayUrl());
        $this->assertEquals($expandedUrl, $urlObject->getExpandedUrl());
        $this->assertEquals($indices, $urlObject->getIndices());
    }
}
