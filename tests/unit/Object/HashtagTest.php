<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterHashtag;
use Twitter\Test\Mock\TwitterObjectMocker;

class HashtagTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker;

    /**
     * @test
     */
    public function testConstructor()
    {
        $text = 'hashtag';
        $indices = $this->getIndices();

        $hashtag = new TwitterHashtag($text, $indices);

        $this->assertEquals($text, $hashtag->getText());
        $this->assertEquals($indices, $hashtag->getIndices());
        $this->assertEquals('#'.$text, $hashtag->__toString());
    }

} 