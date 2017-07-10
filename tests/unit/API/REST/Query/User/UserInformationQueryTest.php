<?php

namespace Twitter\Test\API\REST\Query\User;

use Twitter\API\REST\DTO\UserIdentifier;
use Twitter\API\REST\Query\User\UserInformationQuery;

class UserInformationQueryTest extends \PHPUnit_Framework_TestCase
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
    public function itShouldSerializeWithTrueAndIdIdentifier()
    {
        $query = new UserInformationQuery(
            UserIdentifier::fromId(33),
            true
        );

        $this->assertEquals(
            [
                'user_id' => 33,
                'include_entities' => 'true'
            ],
            $query->toArray()
        );
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalseAndNameIdentifier()
    {
        $query = new UserInformationQuery(
            UserIdentifier::fromScreenName('username'),
            false
        );

        $this->assertEquals(
            [
                'screen_name' => 'username',
                'include_entities' => 'false'
            ],
            $query->toArray()
        );
    }
}
