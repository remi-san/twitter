<?php
namespace Twitter\Object;

use Twitter\TwitterSerializable;

class TwitterEntities implements TwitterSerializable
{
    /**
     * @var TwitterHashtag[]
     */
    private $hashtags;

    /**
     * @var TwitterSymbol[]
     */
    private $symbols;

    /**
     * @var TwitterUrl[]
     */
    private $urls;

    /**
     * @var TwitterUserMention[]
     */
    private $userMentions;

    /**
     * @var TwitterMedia[]
     */
    private $media;

    /**
     * @var TwitterExtendedEntity[]
     */
    private $extendedEntities;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return TwitterExtendedEntity[]
     */
    public function getExtendedEntities()
    {
        return $this->extendedEntities;
    }

    /**
     * @return TwitterHashtag[]
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    /**
     * @return TwitterMedia[]
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @return TwitterSymbol[]
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * @return TwitterUrl[]
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @return TwitterUserMention[]
     */
    public function getUserMentions()
    {
        return $this->userMentions;
    }

    /**
     * Static constructor.
     *
     * @param TwitterHashtag[]        $hashtags
     * @param TwitterUserMention[]    $userMentions
     * @param TwitterUrl[]            $urls
     * @param TwitterMedia[]          $media
     * @param TwitterSymbol[]         $symbols
     * @param TwitterExtendedEntity[] $extendedEntities
     *
     * @return TwitterEntities
     */
    public static function create(
        array $hashtags = array(),
        array $userMentions = array(),
        array $urls = array(),
        array $media = array(),
        array $symbols = array(),
        array $extendedEntities = array()
    ) {
        $obj = new self();

        $obj->extendedEntities = $extendedEntities;
        $obj->hashtags = $hashtags;
        $obj->media = $media;
        $obj->symbols = $symbols;
        $obj->urls = $urls;
        $obj->userMentions = $userMentions;

        return $obj;
    }
}
