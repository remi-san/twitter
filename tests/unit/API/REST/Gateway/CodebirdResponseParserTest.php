<?php

namespace Twitter\Test\API\REST\Gateway;

use Codebird\Codebird;
use Faker\Factory;
use Faker\Generator;
use Mockery\Mock;
use Twitter\API\Exception\TwitterException;
use Twitter\API\REST\DTO\DirectMessageParameters;
use Twitter\API\REST\DTO\FollowParameters;
use Twitter\API\REST\DTO\TweetParameters;
use Twitter\API\REST\DTO\UserIdentifier;
use Twitter\API\REST\Factory\CodebirdFactory;
use Twitter\API\REST\Gateway\CodebirdResponseParser;
use Twitter\API\REST\Gateway\CodebirdTwitterApiGateway;
use Twitter\API\REST\OAuth\AuthenticationToken;
use Twitter\API\REST\Query\DirectMessage\DirectMessageQuery;
use Twitter\API\REST\Query\Stream\UserStreamQuery;
use Twitter\API\REST\Query\Tweet\MentionsTimelineQuery;
use Twitter\API\REST\Query\User\UserInformationQuery;
use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\Response\HttpStatus;
use Twitter\API\REST\Response\LimitedApiRate;
use Twitter\API\REST\Response\UnlimitedApiRate;
use Twitter\API\REST\TwitterApiGateway;

class CodebirdResponseParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var CodebirdResponseParser */
    private $sut;

    public function setUp()
    {
        $this->sut = new CodebirdResponseParser();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldParseAnObjectResponseWithNoRateLimit()
    {
        $object = new \stdClass();
        $object->httpstatus = 200;
        $object->value = 'value';

        $response = $this->sut->parseObject($object);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(new UnlimitedApiRate(), $response->getRate());
        $this->assertEquals(new HttpStatus(200), $response->getHttpStatus());
        $this->assertEquals('value', $response->getContent()->value);
    }

    /**
     * @test
     */
    public function itShouldParseAnObjectResponseWithRateLimit()
    {
        $time = time();

        $object = new \stdClass();
        $object->httpstatus = 200;
        $object->value = 'value';
        $object->rate = [
            'limit' => 10,
            'remaining' => 5,
            'reset' => $time
        ];

        $response = $this->sut->parseObject($object);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(new LimitedApiRate(10, 5, $time), $response->getRate());
        $this->assertEquals(new HttpStatus(200), $response->getHttpStatus());
        $this->assertEquals('value', $response->getContent()->value);
    }

    /**
     * @test
     */
    public function itShouldParseANullObjectResponseWithNoRateLimit()
    {
        $object = new \stdClass();
        $object->httpstatus = 200;

        $response = $this->sut->parseObject($object);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(new UnlimitedApiRate(), $response->getRate());
        $this->assertEquals(new HttpStatus(200), $response->getHttpStatus());
        $this->assertNull($response->getContent());
    }

    /**
     * @test
     */
    public function itShouldParseAListResponseWithNoRateLimit()
    {
        $object = new \stdClass();
        $object->{'0'} = 'zero';
        $object->{'1'} = 'one';
        $object->httpstatus = 200;

        $response = $this->sut->parseList($object);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(new UnlimitedApiRate(), $response->getRate());
        $this->assertEquals(new HttpStatus(200), $response->getHttpStatus());
        $this->assertEquals(
            ['zero', 'one'],
            $response->getContent()
        );
    }

    /**
     * @test
     */
    public function itShouldSendAnErrorIfTheResponseIsAnError()
    {
        $this->setExpectedException(TwitterException::class);

        $error = new \stdClass();
        $error->message = 'my error message';
        $error->code = 33;

        $object = new \stdClass();
        $object->errors = [ $error ];

        $this->sut->parseObject($object);
    }

    /**
     * @test
     */
    public function itShouldSendAnErrorIfTheHttpStatusIsInError()
    {
        $this->setExpectedException(TwitterException::class);

        $object = new \stdClass();
        $object->httpstatus = 500;
        $object->message = 'error';

        $this->sut->parseObject($object);
    }
}
