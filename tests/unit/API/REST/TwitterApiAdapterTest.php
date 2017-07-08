<?php

namespace Twitter\Test\API\REST;

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
use Twitter\API\REST\Gateway\CodebirdTwitterApiGateway;
use Twitter\API\REST\OAuth\AuthenticationToken;
use Twitter\API\REST\Query\DirectMessage\DirectMessageQuery;
use Twitter\API\REST\Query\Stream\UserStreamQuery;
use Twitter\API\REST\Query\Tweet\MentionsTimelineQuery;
use Twitter\API\REST\Query\User\UserInformationQuery;
use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\TwitterApiGateway;

class TwitterApiAdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Generator */
    private $faker;

    /** @var Codebird | Mock */
    private $codebird;

    /** @var CodebirdFactory | Mock */
    private $codebirdFactory;

    /** @var string */
    private $oauthToken;

    /** @var string */
    private $oauthTokenSecret;

    /** @var string */
    private $verificationToken;

    /** @var callable */
    private $callback;

    /** @var string */
    private $query;

    /** @var object */
    private $userInfo;

    /** @var TweetParameters */
    private $tweetParams;

    /** @var DirectMessageParameters */
    private $dmParams;

    /** @var FollowParameters */
    private $followParams;

    /** @var UserIdentifier */
    private $unfollowParams;

    /** @var TwitterApiGateway */
    private $classUnderTest;

    public function setUp()
    {
        $this->faker = Factory::create();

        $this->codebird = \Mockery::mock(Codebird::class);
        $this->codebird->shouldReceive('setReturnFormat');
        $this->codebirdFactory = \Mockery::mock(CodebirdFactory::class);
        $this->codebirdFactory->shouldReceive('build')->andReturn($this->codebird);

        $this->oauthToken = $this->faker->uuid;
        $this->oauthTokenSecret = $this->faker->uuid;

        $this->verificationToken = $this->faker->word;

        $this->callback = function() {};
        $this->query = $this->faker->word;

        $this->tweetParams = new TweetParameters($this->faker->sentence());
        $this->dmParams = new DirectMessageParameters(
            UserIdentifier::fromId($this->faker->randomNumber()),
            $this->faker->sentence()
        );
        $this->followParams = new FollowParameters(UserIdentifier::fromId($this->faker->randomNumber()));
        $this->unfollowParams = UserIdentifier::fromId($this->faker->randomNumber());

        $this->userInfo = new \stdClass();
        $this->userInfo->httpstatus = 200;

        $this->classUnderTest = new CodebirdTwitterApiGateway(
            $this->codebirdFactory,
            $this->faker->uuid,
            $this->faker->uuid
        );
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldAuthenticate()
    {
        $this->assertItWillAuthenticateWithRequestToken();

        $this->classUnderTest->authenticate(new AuthenticationToken($this->oauthToken, $this->oauthTokenSecret));
    }

    /**
     * @test
     */
    public function itShouldReturnAnAuthUrl()
    {
        $this->assertItWillAskForARequestToken();
        $this->assertItWillAuthenticateWithRequestToken();

        $expectedAuthUrl = $this->faker->word;
        $this->codebird->shouldReceive('oauth_authorize')->andReturn($expectedAuthUrl);

        $authUrl = $this->classUnderTest->getAuthenticationUrl();

        self::assertEquals($expectedAuthUrl, $authUrl);
    }

    /**
     * @test
     */
    public function itShouldReturnAnAuthToken()
    {
        $this->givenItWillVerifyVerificationToken();
        $this->assertItWillAuthenticateWithRequestToken();

        $authUrl = $this->classUnderTest->getAuthenticationToken($this->verificationToken);

        self::assertEquals($authUrl, new AuthenticationToken($this->oauthToken, $this->oauthTokenSecret));
    }

    /**
     * @test
     */
    public function itShouldReturnUserInformation()
    {
        $this->assertItWillCallTwitterApiForUserInformation();

        $userInformation = $this->classUnderTest->getUserInformation(
            new UserInformationQuery(UserIdentifier::fromScreenName('RemiSan'))
        );

        self::assertInstanceOf(ApiResponse::class, $userInformation);
        self::assertEquals($this->userInfo, $userInformation->getContent());
    }

    /**
     * @test
     */
    public function itShouldConsumeUserStream()
    {
        $this->assertItWillFollowUserStream();

        $this->classUnderTest->consumeUserStream(
            new UserStreamQuery(),
            $this->callback
        );
    }

    /**
     * @test
     */
    public function itShouldQueryUserMentions()
    {
        $this->assertItWillQueryTwitterStatusAPI();

        $this->classUnderTest->statusesMentionsTimeLine(new MentionsTimelineQuery());
    }

    /**
     * @test
     */
    public function itShouldQueryDirectMessages()
    {
        $this->assertItWillQueryTwitterDirectMessagesAPI();

        $this->classUnderTest->directMessages(new DirectMessageQuery());
    }

    /**
     * @test
     */
    public function itShouldPostANewStatus()
    {
        $this->assertItWillPostNewStatusToAPI();

        $this->classUnderTest->updateStatus($this->tweetParams);
    }

    /**
     * @test
     */
    public function itShouldPostANewDirectMessage()
    {
        $this->assertItWillPostNewDirectMessageToAPI();

        $this->classUnderTest->newDirectMessage($this->dmParams);
    }

    /**
     * @test
     */
    public function itShouldFollow()
    {
        $this->assertItWillPostFollowCommandToAPI();

        $this->classUnderTest->createFriendship($this->followParams);
    }

    /**
     * @test
     */
    public function itShouldUnfollow()
    {
        $this->assertItWillPostUnfollowCommandToAPI();

        $this->classUnderTest->destroyFriendship($this->unfollowParams);
    }

    /**
     * @test
     */
    public function itShouldFailIfTheAPIReturnsAnError()
    {
        $this->setExpectedException(TwitterException::class);

        $this->assertItWillFailPostingNewStatusToAPIWithError();

        $this->classUnderTest->updateStatus($this->tweetParams);
    }

    /**
     * @test
     */
    public function itShouldFailIfTheAPIReturnsABadHttpStatus()
    {
        $this->setExpectedException(TwitterException::class);

        $this->assertItWillFailPostingNewStatusToAPIWithBadHttpStatus();

        $this->classUnderTest->updateStatus($this->tweetParams);
    }

    /**
     * @return \stdClass
     */
    private function getAuthenticationResult()
    {
        $authResult = new \stdClass();
        $authResult->httpstatus = 200;
        $authResult->oauth_token = $this->oauthToken;
        $authResult->oauth_token_secret = $this->oauthTokenSecret;

        return $authResult;
    }

    private function givenItWillVerifyVerificationToken()
    {
        $authResult = $this->getAuthenticationResult();
        $this->codebird
            ->shouldReceive('oauth_accessToken')
            ->with(
                ['oauth_verifier' => $this->verificationToken]
            )->andReturn($authResult);
    }

    private function assertItWillAskForARequestToken()
    {
        $authResult = $this->getAuthenticationResult();
        $this->codebird->shouldReceive('oauth_requestToken')->andReturn($authResult)->once();
    }

    private function assertItWillAuthenticateWithRequestToken()
    {
        $this->codebird
            ->shouldReceive('setToken')
            ->with(
                $this->oauthToken,
                $this->oauthTokenSecret
            );
    }

    private function assertItWillFollowUserStream()
    {
        $this->codebird->shouldReceive('setStreamingCallback')->with($this->callback)->once();
        $this->codebird->shouldReceive('user')->once();
    }

    private function assertItWillQueryTwitterStatusAPI()
    {
        $result = new \stdClass();
        $result->httpstatus = 200;

        $this->codebird
            ->shouldReceive('statuses_mentionsTimeline')
            ->andReturn($result)
            ->once();
    }

    private function assertItWillQueryTwitterDirectMessagesAPI()
    {
        $result = new \stdClass();
        $result->httpstatus = 200;

        $this->codebird
            ->shouldReceive('directMessages')
            ->andReturn($result)
            ->once();
    }

    private function assertItWillPostNewStatusToAPI()
    {
        $result = new \stdClass();
        $result->httpstatus = 200;

        $this->codebird
            ->shouldReceive('statuses_update')
            ->with($this->tweetParams->toArray())
            ->andReturn($result)
            ->once();
    }

    private function assertItWillPostNewDirectMessageToAPI()
    {
        $result = new \stdClass();
        $result->httpstatus = 200;

        $this->codebird
            ->shouldReceive('directMessages_new')
            ->with($this->dmParams->toArray())
            ->andReturn($result)
            ->once();
    }

    private function assertItWillPostFollowCommandToAPI()
    {
        $result = new \stdClass();
        $result->httpstatus = 200;

        $this->codebird
            ->shouldReceive('friendships_create')
            ->with($this->followParams->toArray())
            ->andReturn($result)
            ->once();
    }

    private function assertItWillPostUnfollowCommandToAPI()
    {
        $result = new \stdClass();
        $result->httpstatus = 200;

        $this->codebird
            ->shouldReceive('friendships_destroy')
            ->with($this->unfollowParams->toArray())
            ->andReturn($result)
            ->once();
    }

    private function assertItWillFailPostingNewStatusToAPIWithError()
    {
        $error = new \stdClass();
        $error->message = $this->faker->word;
        $error->code = $this->faker->randomNumber();

        $result = new \stdClass();
        $result->errors = [ $error ];

        $this->codebird
            ->shouldReceive('statuses_update')
            ->with($this->tweetParams->toArray())
            ->andReturn($result);
    }

    private function assertItWillFailPostingNewStatusToAPIWithBadHttpStatus()
    {
        $result = new \stdClass();
        $result->httpstatus = 401;
        $result->message = $this->faker->word;

        $this->codebird
            ->shouldReceive('statuses_update')
            ->with($this->tweetParams->toArray())
            ->andReturn($result);
    }

    private function assertItWillCallTwitterApiForUserInformation()
    {
        $this->codebird
            ->shouldReceive('users_show')
            ->andReturn($this->userInfo)
            ->once();
    }
}
