<?php

namespace Twitter\Test\API\REST\OAuth;

use Faker\Factory;
use Faker\Generator;
use Twitter\API\REST\OAuth\AuthenticationToken;

class AuthenticationTokenTest extends \PHPUnit_Framework_TestCase
{
    /** @var Generator */
    private $faker;

    public function setUp()
    {
        $this->faker = Factory::create();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldBuildAToken()
    {
        $token = $this->faker->uuid;
        $secret = $this->faker->uuid;

        $authToken = new AuthenticationToken($token, $secret);

        $this->assertEquals($token, $authToken->getToken());
        $this->assertEquals($secret, $authToken->getSecret());
    }
}
