<?php
namespace Twitter\Test\Object;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Twitter\Doctrine\TwitterType;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TwitterJsonSerializer;

class TwitterTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractPlatform */
    private $platform;

    /** @var string */
    private $serializedUser;

    /** @var TwitterUser */
    private $user;

    /** @var TwitterJsonSerializer */
    private $twitterJsonSerializer;

    /** @var TwitterType */
    private $twitterType;

    public function setUp()
    {
        $this->platform = \Mockery::mock(AbstractPlatform::class);

        $this->serializedUser = json_encode(new \stdClass());
        $this->user = \Mockery::mock(TwitterUser::class);

        $this->twitterJsonSerializer = \Mockery::mock(TwitterJsonSerializer::class);
        $this->twitterType = $this->getTwitterType();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testProperties()
    {
        $this->assertEquals(TwitterType::TWITTER, $this->twitterType->getName());
        $this->assertEquals(TwitterType::SQL_TYPE, $this->twitterType->getSQLDeclaration([], $this->platform));
    }

    /**
     * @test
     */
    public function itShouldBeAbleToStoreValueInDb()
    {
        $this->twitterJsonSerializer
            ->shouldReceive('serialize')
            ->with($this->user)
            ->andReturn($this->serializedUser)
            ->once();

        $this->twitterType->setSerializer($this->twitterJsonSerializer);

        $this->assertEquals(
            $this->serializedUser,
            $this->twitterType->convertToDatabaseValue($this->user, $this->platform)
        );
    }

    /**
     * @test
     */
    public function itShouldBeAbleToStoreNullValueInDb()
    {
        $this->assertNull($this->twitterType->convertToDatabaseValue(null, $this->platform));
    }

    /**
     * @test
     */
    public function itShouldBeAbleToRetrieveValueFromDb()
    {
        $this->twitterJsonSerializer
            ->shouldReceive('unserialize')
            ->with($this->serializedUser)
            ->andReturn($this->user)
            ->once();

        $this->twitterType->setSerializer($this->twitterJsonSerializer);

        $this->assertEquals($this->user, $this->twitterType->convertToPHPValue($this->serializedUser, $this->platform));
    }

    /**
     * @test
     */
    public function itShouldBeAbleToRetrieveNullValueFromDb()
    {
        $this->assertNull($this->twitterType->convertToPHPValue(null, $this->platform));
    }

    /**
     * @test
     */
    public function testInvalidMessage()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->twitterType->convertToDatabaseValue(new \stdClass(), $this->platform);
    }

    /**
     * @return TwitterType
     */
    private function getTwitterType()
    {
        if (!Type::hasType(TwitterType::TWITTER)) {
            Type::addType(TwitterType::TWITTER, TwitterType::class);
        }

        return Type::getType(TwitterType::TWITTER);
    }
}
