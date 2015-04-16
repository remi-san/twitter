<?php
namespace Twitter\Test\Mock;

use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterCoordinatesSerializer;
use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterEntityIndicesSerializer;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterEventTargetSerializer;
use Twitter\Serializer\TwitterExtendedEntitySerializer;
use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\Serializer\TwitterHashtagSerializer;
use Twitter\Serializer\TwitterMediaSerializer;
use Twitter\Serializer\TwitterMediaSizeSerializer;
use Twitter\Serializer\TwitterPlaceSerializer;
use Twitter\Serializer\TwitterSymbolSerializer;
use Twitter\Serializer\TwitterUrlSerializer;
use Twitter\Serializer\TwitterUserMentionSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\Serializer\TwitterVariantMediaSerializer;

trait TwitterSerializerMocker {

    /**
     * @return TweetSerializer
     */
    public function getTweetSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TweetSerializer');
    }

    /**
     * @return TwitterDirectMessageSerializer
     */
    public function getDirectMessageSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterDirectMessageSerializer');
    }

    /**
     * @return TwitterEntitiesSerializer
     */
    public function getEntitiesSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterEntitiesSerializer');
    }

    /**
     * @return TwitterExtendedEntitySerializer
     */
    public function getExtendedEntitySerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterExtendedEntitySerializer');
    }

    /**
     * @return TwitterUserSerializer
     */
    public function getUserSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterUserSerializer');
    }

    /**
     * @return TwitterPlaceSerializer
     */
    public function getPlaceSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterPlaceSerializer');
    }

    /**
     * @return TwitterCoordinatesSerializer
     */
    public function getCoordinatesSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterCoordinatesSerializer');
    }

    /**
     * @return TwitterHashtagSerializer
     */
    public function getHashtagSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterHashtagSerializer');
    }

    /**
     * @return TwitterMediaSerializer
     */
    public function getMediaSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterMediaSerializer');
    }

    /**
     * @return TwitterSymbolSerializer
     */
    public function getSymbolSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterSymbolSerializer');
    }

    /**
     * @return TwitterUrlSerializer
     */
    public function getUrlSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterUrlSerializer');
    }

    /**
     * @return TwitterUserMentionSerializer
     */
    public function getUserMentionSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterUserMentionSerializer');
    }

    /**
     * @return TwitterEventSerializer
     */
    public function getEventSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterEventSerializer');
    }

    /**
     * @return TwitterEventTargetSerializer
     */
    public function getEventTargetSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterEventTargetSerializer');
    }

    /**
     * @return TwitterFriendsSerializer
     */
    public function getFriendsSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterFriendsSerializer');
    }

    /**
     * @return TwitterDisconnectSerializer
     */
    public function getDisconnectSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterDisconnectSerializer');
    }

    /**
     * @return TwitterDeleteSerializer
     */
    public function getDeleteSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterDeleteSerializer');
    }

    /**
     * @return TwitterEntityIndicesSerializer
     */
    public function getEntityIndicesSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterEntityIndicesSerializer');
    }

    /**
     * @return TwitterMediaSizeSerializer
     */
    public function getMediaSizeSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterMediaSizeSerializer');
    }

    /**
     * @return TwitterVariantMediaSerializer
     */
    public function getVariantMediaSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterVariantMediaSerializer');
    }
} 