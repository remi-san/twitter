<?php
namespace Twitter\Test\Mock;

use Twitter\Serializer\TwitterDeleteSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterDisconnectSerializer;
use Twitter\Serializer\TwitterEntitiesSerializer;
use Twitter\Serializer\TwitterEventSerializer;
use Twitter\Serializer\TwitterExtendedEntitySerializer;
use Twitter\Serializer\TwitterFriendsSerializer;
use Twitter\Serializer\TwitterHashtagSerializer;
use Twitter\Serializer\TwitterMediaSerializer;
use Twitter\Serializer\TwitterPlaceSerializer;
use Twitter\Serializer\TwitterSymbolSerializer;
use Twitter\Serializer\TwitterUrlSerializer;


trait TwitterSerializerMocker {

    /**
     * @return \Twitter\Serializer\TweetSerializer
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
     * @return \Twitter\Serializer\TwitterUserSerializer
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
     * @return \Twitter\Serializer\TwitterCoordinatesSerializer
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
     * @return \Twitter\Serializer\TwitterUserMentionSerializer
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
     * @return \Twitter\Serializer\TwitterEventTargetSerializer
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
     * @return \Twitter\Serializer\TwitterEntityIndicesSerializer
     */
    public function getEntityIndicesSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterEntityIndicesSerializer');
    }

    /**
     * @return \Twitter\Serializer\TwitterMediaSizeSerializer
     */
    public function getMediaSizeSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterMediaSizeSerializer');
    }

    /**
     * @return \Twitter\Serializer\TwitterVariantMediaSerializer
     */
    public function getVariantMediaSerializer()
    {
        return \Mockery::mock('\\Twitter\\Serializer\\TwitterVariantMediaSerializer');
    }
} 