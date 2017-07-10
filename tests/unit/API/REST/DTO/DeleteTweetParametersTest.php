<?php

namespace Twitter\Test\API\REST\DTO;

use Twitter\API\REST\DTO\DeleteTweetParameters;

class DeleteTweetParametersTest extends \PHPUnit_Framework_TestCase
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
        $parameters = new DeleteTweetParameters(33, true);

        $this->assertEquals(['id' => 33, 'trim_user' => 'true'], $parameters->toArray());
    }

    /**
     * @test
     */
    public function itShouldSerializeWithFalseValue()
    {
        $parameters = new DeleteTweetParameters(33, false);

        $this->assertEquals(['id' => 33, 'trim_user' => 'false'], $parameters->toArray());
    }
}
