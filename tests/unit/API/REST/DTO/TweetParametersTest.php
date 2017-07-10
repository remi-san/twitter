<?php

namespace Twitter\Test\API\REST\DTO;

use Twitter\API\REST\DTO\Coordinates;
use Twitter\API\REST\DTO\TweetParameters;
use Twitter\API\REST\DTO\UserIdentifier;

class TweetParametersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Init
     */
    public function setUp()
    {
    }

    /**
     * Close
     */
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldSerializeWithTrueValue()
    {
        $parameters = new TweetParameters(
            'message',
            33,
            true,
            new Coordinates(12, 42),
            'france/paris',
            true,
            true,
            [1, 2, 3],
            true,
            true
        );

        $this->assertEquals(
            [
                'status' => 'message',
                'possibly_sensitive' => 'true',
                'trim_user' => 'true',
                'enable_dm_commands' => 'true',
                'fail_dm_commands' => 'true',
                'in_reply_to_status_id' => 33,
                'lat' => 12,
                'long' => 42,
                'display_coordinates' => 'true',
                'place_id' => 'france/paris',
                'media_ids' => '1,2,3'
            ],
            $parameters->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalseValue()
    {
        $parameters = new TweetParameters(
            'message',
            33,
            false,
            new Coordinates(12, 42),
            'france/paris',
            false,
            false,
            [1, 2, 3],
            false,
            false
        );

        $this->assertEquals(
            [
                'status' => 'message',
                'possibly_sensitive' => 'false',
                'trim_user' => 'false',
                'enable_dm_commands' => 'false',
                'fail_dm_commands' => 'false',
                'in_reply_to_status_id' => 33,
                'lat' => 12,
                'long' => 42,
                'display_coordinates' => 'false',
                'place_id' => 'france/paris',
                'media_ids' => '1,2,3'
            ],
            $parameters->toArray()
        );
    }
}
