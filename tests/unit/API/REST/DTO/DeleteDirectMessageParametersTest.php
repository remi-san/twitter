<?php

namespace Twitter\Test\API\REST\DTO;

use Twitter\API\REST\DTO\DeleteDirectMessageParameters;

class DeleteDirectMessageParametersTest extends \PHPUnit_Framework_TestCase
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
        $parameters = new DeleteDirectMessageParameters(33, true);

        $this->assertEquals(['id' => 33, 'include_entities' => 'true'], $parameters->toArray());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalseValue()
    {
        $parameters = new DeleteDirectMessageParameters(33, false);

        $this->assertEquals(['id' => 33, 'include_entities' => 'false'], $parameters->toArray());
    }
}
