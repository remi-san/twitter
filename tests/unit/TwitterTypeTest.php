<?php
namespace Twitter\Test\Object;

use Doctrine\DBAL\Types\Type;
use Twitter\Doctrine\TwitterType;
use Twitter\Object\TwitterUser;

class TwitterTypeTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    public function test()
    {
        $platform = \Mockery::mock('Doctrine\DBAL\Platforms\AbstractPlatform');

        $serializedUser = '{}';
        $user = new TwitterUser();

        $twitterJsonSerializer = \Mockery::mock('Twitter\Serializer\TwitterJsonSerializer');
        $twitterJsonSerializer->shouldReceive('unserialize')->with($serializedUser)->andReturn($user)->once();
        $twitterJsonSerializer->shouldReceive('serialize')->with($user)->andReturn($serializedUser)->once();

        if (!Type::hasType(TwitterType::TWITTER)) {
            Type::addType(TwitterType::TWITTER, 'Twitter\Doctrine\TwitterType');
        }

        $twitterType = Type::getType(TwitterType::TWITTER);
        $twitterType->setSerializer($twitterJsonSerializer);

        $this->assertEquals(TwitterType::TWITTER, $twitterType->getName());
        $this->assertEquals('TEXT', $twitterType->getSQLDeclaration(array(), $platform));
        $this->assertEquals($serializedUser, $twitterType->convertToDatabaseValue($user, $platform));
        $this->assertEquals($user, $twitterType->convertToPHPValue($serializedUser, $platform));
    }

    public function testInvalidMessage()
    {
        $platform = \Mockery::mock('Doctrine\DBAL\Platforms\AbstractPlatform');
        $user = new \stdClass();

        if (!Type::hasType(TwitterType::TWITTER)) {
            Type::addType(TwitterType::TWITTER, 'Twitter\Doctrine\TwitterType');
        }

        $twitterType = Type::getType(TwitterType::TWITTER);

        $this->setExpectedException(\InvalidArgumentException::class);
        $twitterType->convertToDatabaseValue($user, $platform);
    }
}
