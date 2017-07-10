<?php

namespace Twitter\Test\API\REST\DTO;

use Twitter\API\REST\DTO\UserIdentifier;

class UserIdentifierTest extends \PHPUnit_Framework_TestCase
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
        $identifier = UserIdentifier::fromId(33);

        $this->assertEquals(['user_id' => 33], $identifier->toArray());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithNameIdentifier()
    {
        $identifier = UserIdentifier::fromScreenName('username');

        $this->assertEquals(['screen_name' => 'username'], $identifier->toArray());
    }
}
