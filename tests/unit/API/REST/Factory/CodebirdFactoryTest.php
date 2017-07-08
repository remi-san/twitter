<?php
namespace Twitter\Test\API\REST\Factory;

use Codebird\Codebird;
use Faker\Factory;
use Faker\Generator;
use Twitter\API\REST\Factory\CodebirdFactory;

class CodebirdFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Generator */
    private $faker;

    /** @var CodebirdFactory */
    private $factory;

    public function setUp()
    {
        $this->faker = Factory::create();

        $this->factory = new CodebirdFactory();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldGetACodebirdSingleton()
    {
        $codebird = $this->factory->build(
            $this->faker->word,
            $this->faker->word
        );

        $this->assertInstanceOf(Codebird::class, $codebird);
    }
}
