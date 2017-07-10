<?php

namespace Twitter\Test\API\REST\DTO;

use Twitter\API\REST\DTO\DirectMessageParameters;
use Twitter\API\REST\DTO\UserIdentifier;

class DirectMessageParametersTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldSerializeWithIdIdentifier()
    {
        $parameters = new DirectMessageParameters(
            UserIdentifier::fromId(33),
            'message'
        );

        $this->assertEquals(['user_id' => 33, 'text' => 'message'], $parameters->toArray());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithNameIdentifier()
    {
        $parameters = new DirectMessageParameters(
            UserIdentifier::fromScreenName('username'),
            'message'
        );

        $this->assertEquals(['screen_name' => 'username', 'text' => 'message'], $parameters->toArray());
    }
}
