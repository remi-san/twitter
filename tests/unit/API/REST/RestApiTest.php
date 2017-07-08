<?php
namespace Twitter\Test\API\REST;

use Mockery\Mock;
use Psr\Log\LoggerInterface;
use Twitter\API\REST\Client\TwitterApiClient;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterMessageId;
use Twitter\API\REST\DTO\DirectMessageParameters;
use Twitter\API\REST\DTO\FollowParameters;
use Twitter\API\REST\DTO\TweetParameters;
use Twitter\API\REST\DTO\UserIdentifier;
use Twitter\API\REST\Query\DirectMessage\DirectMessageQuery;
use Twitter\API\REST\Query\Tweet\MentionsTimelineQuery;
use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\Response\HttpStatus;
use Twitter\API\REST\Response\UnlimitedApiRate;
use Twitter\API\REST\TwitterApiGateway;
use Twitter\API\REST\TwitterClient;

class RestApiTest extends \PHPUnit_Framework_TestCase
{
    /** @var int */
    private $from;

    /** @var int */
    private $to;

    /** @var int */
    private $stepId;

    /** @var int */
    private $minId;

    /** @var int */
    private $topId;

    /** @var string */
    private $message;

    /** @var int */
    private $userId;

    /** @var object */
    private $firstSerializedDirectMessage;

    /** @var object */
    private $secondSerializedDirectMessage;

    /** @var object */
    private $thirdSerializedDirectMessage;

    /** @var object */
    private $firstSerializedTweet;

    /** @var object */
    private $secondSerializedTweet;

    /** @var object */
    private $thirdSerializedTweet;

    /** @var object */
    private $serializedErrorMessage;

    /** @var TwitterDirectMessage | Mock */
    private $directMessage;

    /** @var TwitterDirectMessage | Mock */
    private $secondDirectMessage;

    /** @var TwitterDirectMessage | Mock */
    private $thirdDirectMessage;

    /** @var Tweet | Mock */
    private $tweet;

    /** @var Tweet | Mock */
    private $secondTweet;

    /** @var Tweet | Mock */
    private $thirdTweet;

    /** @var TwitterUser | Mock */
    private $user;

    /** @var TwitterUserSerializer | Mock */
    private $userSerializer;

    /** @var TweetSerializer | Mock */
    private $tweetSerializer;

    /** @var TwitterDirectMessageSerializer | Mock */
    private $dmSerializer;

    /** @var TwitterApiGateway | Mock */
    private $adapter;

    /** @var LoggerInterface | Mock */
    private $logger;

    /** @var TwitterClient */
    private $restApi;

    /**
     * @param array|object|null $content
     *
     * @return ApiResponse
     */
    private static function getResponse($content)
    {
        return new ApiResponse(new HttpStatus(200), $content, new UnlimitedApiRate());
    }

    /**
     * Test setup
     */
    public function setUp()
    {
        $this->from = 0;
        $this->to = 220;

        $this->topId = 100;
        $this->stepId = 50;
        $this->minId = 10;

        $this->message = 'my message';
        $this->userId  = 42;

        $this->firstSerializedDirectMessage = $this->getSerializedDirectMessage($this->topId);
        $this->secondSerializedDirectMessage = $this->getSerializedDirectMessage($this->stepId);
        $this->thirdSerializedDirectMessage = $this->getSerializedDirectMessage($this->minId);

        $this->firstSerializedTweet = $this->getSerializedTweet($this->topId);
        $this->secondSerializedTweet = $this->getSerializedTweet($this->stepId);
        $this->thirdSerializedTweet = $this->getSerializedTweet($this->minId);

        $this->serializedErrorMessage = $this->getSerializedErrorMessage();
        
        $this->directMessage = $this->getDirectMessage($this->topId);
        $this->secondDirectMessage = $this->getDirectMessage($this->stepId);
        $this->thirdDirectMessage = $this->getDirectMessage($this->minId);

        $this->tweet = $this->getTweet(TwitterMessageId::create($this->topId));
        $this->secondTweet = $this->getTweet(TwitterMessageId::create($this->stepId));
        $this->thirdTweet = $this->getTweet(TwitterMessageId::create($this->minId));

        $this->user = $this->getUser($this->userId);

        $this->userSerializer = \Mockery::mock(TwitterUserSerializer::class);
        $this->tweetSerializer = \Mockery::mock(TweetSerializer::class);
        $this->dmSerializer = \Mockery::mock(TwitterDirectMessageSerializer::class);
        $this->logger = \Mockery::spy(LoggerInterface::class);

        $this->adapter = \Mockery::mock(TwitterApiGateway::class);

        $this->restApi = new TwitterApiClient(
            $this->adapter,
            $this->userSerializer,
            $this->tweetSerializer,
            $this->dmSerializer
        );
        $this->restApi->setLogger($this->logger);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldGetUserInformation()
    {
        $this->givenUserInformationAPICallWillReturnUserInfo();

        $user = $this->restApi->getUser('RemiSan');

        $this->assertEquals($this->user, $user);
    }

    /**
     * @test
     */
    public function itShouldNotGetDirectMessages()
    {
        $this->givenDirectMessageAPICallWillNotReturnDirectMessages();

        $dms = $this->restApi->getDirectMessages($this->from, $this->to);

        $this->assertEquals([], $dms);
    }

    /**
     * @test
     */
    public function itShouldGetDirectMessagesInOneAPICall()
    {
        $return = [
            $this->stepId  => $this->secondDirectMessage,
            $this->topId => $this->directMessage
        ];

        $this->givenDirectMessageAPICallWillReturnDirectMessages();
        $this->givenSecondDirectMessageAPICallWillNotReturnDirectMessages();

        $this->givenDirectMessageSerializerCanUnserializeFirstDirectMessage();
        $this->givenDirectMessageSerializerCanUnserializeSecondDirectMessage();

        $dms = $this->restApi->getDirectMessages($this->from, $this->to);

        $this->assertEquals($return, $dms);
    }

    /**
     * @test
     */
    public function itShouldGetDirectMessagesInMultipleAPICall()
    {
        $return = [
            $this->minId => $this->thirdDirectMessage,
            $this->stepId  => $this->secondDirectMessage,
            $this->topId => $this->directMessage
        ];

        $this->givenDirectMessageAPICallWillReturnDirectMessages();
        $this->givenSecondDirectMessageAPICallWillReturnOtherDirectMessages();
        $this->givenThirdDirectMessageAPICallWillNotReturnDirectMessages();

        $this->givenDirectMessageSerializerCanUnserializeFirstDirectMessage();
        $this->givenDirectMessageSerializerCanUnserializeSecondDirectMessage();
        $this->givenDirectMessageSerializerCanUnserializeThirdDirectMessage();

        $dms = $this->restApi->getDirectMessages($this->from, $this->to);

        $this->assertEquals($return, $dms);
    }

    /**
     * @test
     */
    public function itShouldNotGetTweets()
    {
        $this->givenTweetAPICallWillNotReturnTweets();

        $tweets = $this->restApi->getMentionsTweets($this->from, $this->to);

        $this->assertEquals([], $tweets);
    }

    /**
     * @test
     */
    public function itShouldGetTweetsInOneAPICall()
    {
        $return = [
            $this->stepId  => $this->secondTweet,
            $this->topId => $this->tweet
        ];

        $this->givenTweetAPICallWillReturnTweets();
        $this->givenSecondTweetAPICallWillNotReturnTweets();

        $this->givenTweetSerializerCanUnserializeFirstTweet();
        $this->givenTweetSerializerCanUnserializeSecondTweet();

        $tweets = $this->restApi->getMentionsTweets($this->from, $this->to);

        $this->assertEquals($return, $tweets);
    }

    /**
     * @test
     */
    public function itShouldGetTweetsInMultipleAPICall()
    {
        $return = [
            $this->minId  => $this->thirdTweet,
            $this->stepId => $this->secondTweet,
            $this->topId  => $this->tweet
        ];

        $this->givenTweetAPICallWillReturnTweets();
        $this->givenSecondTweetAPICallWillReturnTweets();
        $this->givenThirdTweetAPICallWillNotReturnTweets();

        $this->givenTweetSerializerCanUnserializeFirstTweet();
        $this->givenTweetSerializerCanUnserializeSecondTweet();
        $this->givenTweetSerializerCanUnserializeThirdTweet();

        $tweets = $this->restApi->getMentionsTweets($this->from, $this->to);

        $this->assertEquals($return, $tweets);
    }

    /**
     * @test
     */
    public function itShouldSendADirectMessageSuccessfully()
    {
        $this->givenNewDirectMessageAPICallSucceeds();

        $this->restApi->sendDirectMessage($this->user, $this->message);
    }

    /**
     * @test
     */
    public function itShouldSendATweetSuccessfully()
    {
        $this->givenNewTweetAPICallSucceeds();

        $this->restApi->sendTweet($this->message);
    }

    /**
     * @test
     */
    public function itShouldSendATweetReplySuccessfully()
    {
        $this->givenNewTweetReplyAPICallSucceeds();

        $this->restApi->sendTweet($this->message, $this->tweet);
    }

    /**
     * @test
     */
    public function itShouldFollow()
    {
        $this->givenFollowAPICallSucceeds();

        $this->restApi->follow($this->user);
    }

    /**
     * @test
     */
    public function itShouldUnfollow()
    {
        $this->givenUnfollowAPICallSucceeds();

        $this->restApi->unfollow($this->user);
    }

    /**
     * @return \stdClass
     */
    private function getSerializedErrorMessage()
    {
        $error = new \stdClass();
        $error->message = 'Need to build better computer!';
        $error->code = 42;
        
        $errorMessage = new \stdClass();
        $errorMessage->errors = [$error];
        
        return $errorMessage;
    }

    /**
     * @param int $id
     *
     * @return \stdClass
     */
    private function getSerializedDirectMessage($id)
    {
        $directMessage = new \stdClass();
        $directMessage->id = $id;
        
        return $directMessage;
    }

    /**
     * @param int $id
     *
     * @return \stdClass
     */
    private function getSerializedTweet($id)
    {
        $tweet = new \stdClass();
        $tweet->id = $id;
        
        return $tweet;
    }

    /**
     * @param $id
     *
     * @return TwitterDirectMessage
     */
    private function getDirectMessage($id)
    {
        /** @var TwitterDirectMessage | Mock $directMessage */
        $directMessage = \Mockery::mock(TwitterDirectMessage::class);
        $directMessage->shouldReceive('getId')->andReturn($id);
        
        return $directMessage;
    }

    /**
     * @param $id
     *
     * @return Tweet
     */
    private function getTweet($id)
    {
        /** @var Tweet | Mock $tweet */
        $tweet = \Mockery::mock(Tweet::class);
        $tweet->shouldReceive('getId')->andReturn(TwitterMessageId::create($id));
        
        return $tweet;
    }

    /**
     * @param $id
     *
     * @return TwitterUser
     */
    private function getUser($id)
    {
        /** @var TwitterUser | Mock $user */
        $user = \Mockery::mock(TwitterUser::class);
        $user->shouldReceive('getId')->andReturn($id);
        
        return $user;
    }

    private function givenDirectMessageAPICallWillNotReturnDirectMessages()
    {
        $this->adapter
            ->shouldReceive('directMessages')
            ->with(\Mockery::on(function (DirectMessageQuery $query) {
                return $query == new DirectMessageQuery(200, $this->from, $this->to);
            }))
            ->andReturn(self::getResponse([]));
    }

    private function givenDirectMessageAPICallWillReturnDirectMessages()
    {
        $this->adapter
            ->shouldReceive('directMessages')
            ->with(\Mockery::on(function (DirectMessageQuery $query) {
                return $query == new DirectMessageQuery(200, $this->from, $this->to);
            }))
            ->andReturn(self::getResponse([
                $this->topId => $this->firstSerializedDirectMessage,
                $this->stepId => $this->secondSerializedDirectMessage
            ]));
    }

    private function givenSecondDirectMessageAPICallWillNotReturnDirectMessages()
    {
        $this->adapter
            ->shouldReceive('directMessages')
            ->with(\Mockery::on(function (DirectMessageQuery $query) {
                return $query == new DirectMessageQuery(200, $this->from, $this->stepId - 1);
            }))
            ->andReturn(self::getResponse([]));
    }

    private function givenSecondDirectMessageAPICallWillReturnOtherDirectMessages()
    {
        $this->adapter
            ->shouldReceive('directMessages')
            ->with(\Mockery::on(function (DirectMessageQuery $query) {
                return $query == new DirectMessageQuery(200, $this->from, $this->stepId - 1);
            }))
            ->andReturn(self::getResponse([$this->minId => $this->thirdSerializedDirectMessage]));
    }

    private function givenThirdDirectMessageAPICallWillNotReturnDirectMessages()
    {
        $this->adapter
            ->shouldReceive('directMessages')
            ->with(\Mockery::on(function (DirectMessageQuery $query) {
                return $query == new DirectMessageQuery(200, $this->from, $this->minId - 1);
            }))
            ->andReturn(self::getResponse([]));
    }

    private function givenDirectMessageSerializerCanUnserializeFirstDirectMessage()
    {
        $this->dmSerializer
            ->shouldReceive('unserialize')
            ->with($this->firstSerializedDirectMessage)
            ->andReturn($this->directMessage);
    }

    private function givenDirectMessageSerializerCanUnserializeSecondDirectMessage()
    {
        $this->dmSerializer
            ->shouldReceive('unserialize')
            ->with($this->secondSerializedDirectMessage)
            ->andReturn($this->secondDirectMessage);
    }

    private function givenDirectMessageSerializerCanUnserializeThirdDirectMessage()
    {
        $this->dmSerializer
            ->shouldReceive('unserialize')
            ->with($this->thirdSerializedDirectMessage)
            ->andReturn($this->thirdDirectMessage);
    }

    private function givenTweetAPICallWillNotReturnTweets()
    {
        $this->adapter
            ->shouldReceive('statusesMentionsTimeLine')
            ->with(\Mockery::on(function (MentionsTimelineQuery $query) {
                return $query == new MentionsTimelineQuery(200, $this->from, $this->to);
            }))
            ->andReturn(self::getResponse([]));
    }

    private function givenTweetAPICallWillReturnTweets()
    {
        $this->adapter
            ->shouldReceive('statusesMentionsTimeLine')
            ->with(\Mockery::on(function (MentionsTimelineQuery $query) {
                return $query == new MentionsTimelineQuery(200, $this->from, $this->to);
            }))
            ->andReturn(self::getResponse([
                $this->topId => $this->firstSerializedTweet,
                $this->stepId => $this->secondSerializedTweet
            ]));
    }

    private function givenSecondTweetAPICallWillNotReturnTweets()
    {
        $this->adapter
            ->shouldReceive('statusesMentionsTimeLine')
            ->with(\Mockery::on(function (MentionsTimelineQuery $query) {
                return $query == new MentionsTimelineQuery(200, $this->from, $this->stepId - 1);
            }))
            ->andReturn(self::getResponse([]));
    }

    private function givenSecondTweetAPICallWillReturnTweets()
    {
        $this->adapter
            ->shouldReceive('statusesMentionsTimeLine')
            ->with(\Mockery::on(function (MentionsTimelineQuery $query) {
                return $query == new MentionsTimelineQuery(200, $this->from, $this->stepId - 1);
            }))
            ->andReturn(self::getResponse([$this->minId => $this->thirdSerializedTweet]));
    }

    private function givenThirdTweetAPICallWillNotReturnTweets()
    {
        $this->adapter
            ->shouldReceive('statusesMentionsTimeLine')
            ->with(\Mockery::on(function (MentionsTimelineQuery $query) {
                return $query == new MentionsTimelineQuery(200, $this->from, $this->minId - 1);
            }))
            ->andReturn(self::getResponse([]));
    }

    private function givenTweetSerializerCanUnserializeFirstTweet()
    {
        $this->tweetSerializer
            ->shouldReceive('unserialize')
            ->with($this->firstSerializedTweet)
            ->andReturn($this->tweet);
    }

    private function givenTweetSerializerCanUnserializeSecondTweet()
    {
        $this->tweetSerializer
            ->shouldReceive('unserialize')
            ->with($this->secondSerializedTweet)
            ->andReturn($this->secondTweet);
    }

    private function givenTweetSerializerCanUnserializeThirdTweet()
    {
        $this->tweetSerializer
            ->shouldReceive('unserialize')
            ->with($this->thirdSerializedTweet)
            ->andReturn($this->thirdTweet);
    }

    private function givenNewDirectMessageAPICallSucceeds()
    {
        $this->adapter
            ->shouldReceive('newDirectMessage')
            ->with(\Mockery::on(function (DirectMessageParameters $params) {
                return $params == new DirectMessageParameters(
                    UserIdentifier::fromId($this->userId),
                    $this->message
                );
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }

    private function givenNewTweetAPICallSucceeds()
    {
        $this->adapter
            ->shouldReceive('updateStatus')
            ->with(\Mockery::on(function (TweetParameters $params) {
                return $params == new TweetParameters($this->message);
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }

    private function givenNewTweetReplyAPICallSucceeds()
    {
        $this->adapter
            ->shouldReceive('updateStatus')
            ->with(\Mockery::on(function (TweetParameters $params) {
                return $params == new TweetParameters($this->message, $this->topId);
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }

    private function givenFollowAPICallSucceeds()
    {
        $this->adapter
            ->shouldReceive('createFriendship')
            ->with(\Mockery::on(function (FollowParameters $params) {
                return $params == new FollowParameters(UserIdentifier::fromId($this->userId));
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }

    private function givenUnfollowAPICallSucceeds()
    {
        $this->adapter
            ->shouldReceive('destroyFriendship')
            ->with(\Mockery::on(function (UserIdentifier $params) {
                return $params == UserIdentifier::fromId($this->userId);
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }

    private function givenUserInformationAPICallWillReturnUserInfo()
    {
        $this->adapter
            ->shouldReceive('getUserInformation')
            ->andReturn(self::getResponse([]))
            ->once();
        $this->userSerializer->shouldReceive('unserialize')->andReturn($this->user)->once();
    }
}
