<?php

namespace Twitter\Test\API\REST\DTO;

use Twitter\API\REST\DTO\FollowParameters;
use Twitter\API\REST\DTO\UserIdentifier;

class FollowParametersTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldSerializeWithIdIdentifierAndTrueValue()
    {
        $parameters = new FollowParameters(
            UserIdentifier::fromId(33),
            true
        );

        $this->assertEquals(['user_id' => 33, 'follow' => 'true'], $parameters->toArray());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithNameIdentifierAndFalseValue()
    {
        $parameters = new FollowParameters(
            UserIdentifier::fromScreenName('username'),
            false
        );

        $this->assertEquals(['screen_name' => 'username', 'follow' => 'false'], $parameters->toArray());
    }
}
