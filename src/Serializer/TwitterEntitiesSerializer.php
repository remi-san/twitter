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
    public function __construct(
        TwitterExtendedEntitySerializer $extendedEntitySerializer,
        TwitterHashtagSerializer $hashtagSerializer,
        TwitterMediaSerializer $mediaSerializer,
        TwitterSymbolSerializer $symbolSerializer,
        TwitterUrlSerializer $urlSerializer,
        TwitterUserMentionSerializer $userMentionSerializer
    ) {
        $this->extendedEntitySerializer = $extendedEntitySerializer;
        $this->hashtagSerializer = $hashtagSerializer;
        $this->mediaSerializer = $mediaSerializer;
        $this->symbolSerializer = $symbolSerializer;
        $this->urlSerializer = $urlSerializer;
        $this->userMentionSerializer = $userMentionSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterEntities');
        }

        $entities = new \stdClass();

        // Hashtags
        if ($object->getHashtags()) {
            $entities->hashtags = [];
            foreach ($object->getHashtags() as $hashtag) {
                $entities->hashtags[] = $this->hashtagSerializer->serialize($hashtag);
            }
        }

        // Symbols
        if ($object->getSymbols()) {
            $entities->symbols = [];
            foreach ($object->getSymbols() as $symbol) {
                $entities->symbols[] = $this->symbolSerializer->serialize($symbol);
            }
        }

        // Urls
        if ($object->getUrls()) {
            $entities->urls = [];
            foreach ($object->getUrls() as $url) {
                $entities->urls[] = $this->urlSerializer->serialize($url);
            }
        }

        // User mentions
        if ($object->getUserMentions()) {
            $entities->user_mentions = [];
            foreach ($object->getUserMentions() as $userMention) {
                $entities->user_mentions[] = $this->userMentionSerializer->serialize($userMention);
            }
        }

        // Media
        if ($object->getMedia()) {
            $entities->media = [];
            foreach ($object->getMedia() as $media) {
                $entities->media[] = $this->mediaSerializer->serialize($media);
            }
        }

        // Extended entities
        if ($object->getExtendedEntities()) {
            $entities->extended_entities = [];
            foreach ($object->getExtendedEntities() as $extendedEntity) {
                $entities->extended_entities[] = $this->extendedEntitySerializer->serialize($extendedEntity);
            }
        }

        return $entities;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterEntities
     */
    public function unserialize($obj, array $context = [])
    {
        if (!$this->canUnserialize($obj)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        // Hashtags
        $hashtags = [];
        if (isset($obj->hashtags)) {
            foreach ($obj->hashtags as $hashtag) {
                $hashtags[] = $this->hashtagSerializer->unserialize($hashtag);
            }
        }

        // Symbols
        $symbols = [];
        if (isset($obj->symbols)) {
            foreach ($obj->symbols as $symbol) {
                $symbols[] = $this->symbolSerializer->unserialize($symbol);
            }
        }

        // URLs
        $urls = [];
        if (isset($obj->urls)) {
            foreach ($obj->urls as $url) {
                $urls[] = $this->urlSerializer->unserialize($url);
            }
        }

        // User mentions
        $userMentions = [];
        if (isset($obj->user_mentions)) {
            foreach ($obj->user_mentions as $userMention) {
                $userMentions[] = $this->userMentionSerializer->unserialize($userMention);
            }
        }

        // Media
        $media = [];
        if (isset($obj->media)) {
            foreach ($obj->media as $medium) {
                $media[] = $this->mediaSerializer->unserialize($medium);
            }
        }

        // Extended entities
        $extendedEntities = [];
        if (isset($obj->extended_entities)) {
            foreach ($obj->extended_entities as $extendedEntity) {
                $extendedEntities[] = $this->extendedEntitySerializer->unserialize($extendedEntity);
            }
        }

        return TwitterEntities::create($hashtags, $userMentions, $urls, $media, $symbols, $extendedEntities);
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterEntities;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->hashtags) ||  isset($object->symbols) ||  isset($object->urls) ||
            isset($object->user_mentions) || isset($object->media) || isset($object->extended_entities);
    }

    /**
     * @return TwitterEntitiesSerializer
     */
    public static function build()
    {
        return new self(
            TwitterExtendedEntitySerializer::build(),
            TwitterHashtagSerializer::build(),
            TwitterMediaSerializer::build(),
            TwitterSymbolSerializer::build(),
            TwitterUrlSerializer::build(),
            TwitterUserMentionSerializer::build()
        );
    }
}
