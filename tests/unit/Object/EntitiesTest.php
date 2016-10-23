<?php
namespace Twitter\Test\Object;

use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterExtendedEntity;
use Twitter\Object\TwitterHashtag;
use Twitter\Object\TwitterMedia;
use Twitter\Object\TwitterSymbol;
use Twitter\Object\TwitterUrl;
use Twitter\Object\TwitterUserMention;

class EntitiesTest extends \PHPUnit_Framework_TestCase
{
    /** @var TwitterHashtag[] */
    private $hashtags;

    /** @var TwitterSymbol[] */
    private $symbols;

    /** @var TwitterUrl[] */
    private $urls;

    /** @var TwitterUserMention[] */
    private $userMentions;

    /** @var TwitterMedia[] */
    private $media;

    /** @var TwitterExtendedEntity[] */
    private $extendedEntities;

    public function setUp()
    {
        $this->hashtags = [\Mockery::mock(TwitterHashtag::class)];
        $this->symbols = [\Mockery::mock(TwitterSymbol::class)];
        $this->urls = [\Mockery::mock(TwitterUrl::class)];
        $this->userMentions = [\Mockery::mock(TwitterUserMention::class)];
        $this->media = [\Mockery::mock(TwitterMedia::class)];
        $this->extendedEntities = [\Mockery::mock(TwitterExtendedEntity::class)];
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
        $entities = TwitterEntities::create(
            $this->hashtags,
            $this->userMentions,
            $this->urls,
            $this->media,
            $this->symbols,
            $this->extendedEntities
        );

        $this->assertEquals($this->hashtags, $entities->getHashtags());
        $this->assertEquals($this->symbols, $entities->getSymbols());
        $this->assertEquals($this->urls, $entities->getUrls());
        $this->assertEquals($this->userMentions, $entities->getUserMentions());
        $this->assertEquals($this->media, $entities->getMedia());
        $this->assertEquals($this->extendedEntities, $entities->getExtendedEntities());
    }
}
