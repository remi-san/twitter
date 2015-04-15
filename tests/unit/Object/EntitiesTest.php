<?php
namespace Twitter\Test\Object;

use Twitter\Test\Mock\TwitterObjectMocker;

class EntitiesTest extends \PHPUnit_Framework_TestCase {
    use TwitterObjectMocker;

    /**
     * @test
     */
    public function testConstructor()
    {
        $hashtags = array($this->getHashTag('ht'));
        $symbols = array($this->getSymbol());
        $urls = array($this->getUrl());
        $userMentions = array($this->getUserMention('um'));
        $media = array($this->getMedia());
        $extendedEntities = array($this->getExtendedEntity());

        $entities = new \Twitter\Object\TwitterEntities($hashtags, $userMentions, $urls, $media, $symbols, $extendedEntities);

        $this->assertEquals($hashtags, $entities->getHashtags());
        $this->assertEquals($symbols, $entities->getSymbols());
        $this->assertEquals($urls, $entities->getUrls());
        $this->assertEquals($userMentions, $entities->getUserMentions());
        $this->assertEquals($media, $entities->getMedia());
        $this->assertEquals($extendedEntities, $entities->getExtendedEntities());
    }
} 