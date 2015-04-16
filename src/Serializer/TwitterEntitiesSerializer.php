<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterEntities;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterEntitiesSerializer implements TwitterSerializer
{

    /**
     * @var TwitterHashtagSerializer
     */
    private $hashtagSerializer;

    /**
     * @var TwitterSymbolSerializer
     */
    private $symbolSerializer;

    /**
     * @var TwitterUrlSerializer
     */
    private $urlSerializer;

    /**
     * @var TwitterUserMentionSerializer
     */
    private $userMentionSerializer;

    /**
     * @var TwitterMediaSerializer
     */
    private $mediaSerializer;

    /**
     * @var TwitterExtendedEntitySerializer
     */
    private $extendedEntitySerializer;

    /**
     * @param TwitterExtendedEntitySerializer $extendedEntitySerializer
     * @param TwitterHashtagSerializer $hashtagSerializer
     * @param TwitterMediaSerializer $mediaSerializer
     * @param TwitterSymbolSerializer $symbolSerializer
     * @param TwitterUrlSerializer $urlSerializer
     * @param TwitterUserMentionSerializer $userMentionSerializer
     */
    function __construct(
        TwitterExtendedEntitySerializer $extendedEntitySerializer,
        TwitterHashtagSerializer $hashtagSerializer,
        TwitterMediaSerializer $mediaSerializer,
        TwitterSymbolSerializer $symbolSerializer,
        TwitterUrlSerializer $urlSerializer,
        TwitterUserMentionSerializer $userMentionSerializer
    )
    {
        $this->extendedEntitySerializer = $extendedEntitySerializer;
        $this->hashtagSerializer = $hashtagSerializer;
        $this->mediaSerializer = $mediaSerializer;
        $this->symbolSerializer = $symbolSerializer;
        $this->urlSerializer = $urlSerializer;
        $this->userMentionSerializer = $userMentionSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterEntities)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEntities');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterEntities
     */
    public function unserialize($obj, array $context = array())
    {
        // Hashtags
        $hashtags = array();
        if (isset($obj->hashtags)) {
            foreach ($obj->hashtags as $hashtag) {
                $hashtags[] = $this->hashtagSerializer->unserialize($hashtag);
            }
        }

        // Symbols
        $symbols = array();
        if (isset($obj->symbols)) {
            foreach ($obj->symbols as $symbol) {
                $symbols[] = $this->symbolSerializer->unserialize($symbol);
            }
        }

        // URLs
        $urls = array();
        if (isset($obj->urls)) {
            foreach ($obj->urls as $url) {
                $urls[] = $this->urlSerializer->unserialize($url);
            }
        }

        // User mentions
        $userMentions = array();
        if (isset($obj->user_mentions)) {
            foreach ($obj->user_mentions as $userMention) {
                $userMentions[] = $this->userMentionSerializer->unserialize($userMention);
            }
        }

        // Media
        $media = array();
        if (isset($obj->media)) {
            foreach ($obj->media as $medium) {
                $media[] = $this->mediaSerializer->unserialize($medium);
            }
        }

        // Extended entities
        $extendedEntities = array();
        if (isset($obj->extended_entities)) {
            foreach ($obj->extended_entities as $extendedEntity) {
                $extendedEntities[] = $this->extendedEntitySerializer->unserialize($extendedEntity);
            }
        }

        return new TwitterEntities($hashtags, $userMentions, $urls, $media, $symbols, $extendedEntities);
    }
} 