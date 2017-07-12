<?php
namespace Twitter\Test\API\REST\Client;

use Mockery\Mock;
use Psr\Log\LoggerInterface;
use Twitter\API\Exception\TwitterRateLimitException;
use Twitter\API\REST\Client\TwitterApiClient;
use Twitter\API\REST\DTO\DeleteDirectMessageParameters;
use Twitter\API\REST\DTO\DeleteTweetParameters;
use Twitter\API\REST\DTO\DirectMessageParameters;
use Twitter\API\REST\DTO\FollowParameters;
use Twitter\API\REST\DTO\TweetParameters;
use Twitter\API\REST\DTO\UserIdentifier;
use Twitter\API\REST\Query\DirectMessage\DirectMessageQuery;
use Twitter\API\REST\Query\DirectMessage\SentDirectMessageQuery;
use Twitter\API\REST\Query\Friends\FriendsListQuery;
use Twitter\API\REST\Query\Tweet\MentionsTimelineQuery;
use Twitter\API\REST\Query\Tweet\UserTimelineQuery;
use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\Response\HttpStatus;
use Twitter\API\REST\Response\LimitedApiRate;
use Twitter\API\REST\Response\UnlimitedApiRate;
use Twitter\API\REST\TwitterApiGateway;
use Twitter\API\REST\TwitterClient;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\TwitterMessageId;

class TwitterClientTest extends \PHPUnit_Framework_TestCase
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

    /** @var string */
    private $userName;

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
    private $firstSerializedFriend;

    /** @var object */
    private $secondSerializedFriend;

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

    /** @var TwitterUser */
    private $firstFriend;

    /** @var TwitterUser */
    private $secondFriend;

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

    /** @var TwitterApiClient */
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
        $this->userName = 'RemiSan';

        $this->firstSerializedDirectMessage = $this->getSerializedDirectMessage($this->topId);
        $this->secondSerializedDirectMessage = $this->getSerializedDirectMessage($this->stepId);
        $this->thirdSerializedDirectMessage = $this->getSerializedDirectMessage($this->minId);

        $this->firstSerializedTweet = $this->getSerializedTweet($this->topId);
        $this->secondSerializedTweet = $this->getSerializedTweet($this->stepId);
        $this->thirdSerializedTweet = $this->getSerializedTweet($this->minId);

        $this->firstSerializedFriend = $this->getSerializedFriend('1');
        $this->secondSerializedFriend = $this->getSerializedFriend('2');

        $this->serializedErrorMessage = $this->getSerializedErrorMessage();
        
        $this->directMessage = $this->getDirectMessage($this->topId);
        $this->secondDirectMessage = $this->getDirectMessage($this->stepId);
        $this->thirdDirectMessage = $this->getDirectMessage($this->minId);

        $this->tweet = $this->getTweet(TwitterMessageId::create($this->topId));
        $this->secondTweet = $this->getTweet(TwitterMessageId::create($this->stepId));
        $this->thirdTweet = $this->getTweet(TwitterMessageId::create($this->minId));

        $this->firstFriend = $this->getFriend('1');
        $this->secondFriend = $this->getFriend('2');

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
    public function itShouldFailIfApiRateHasBeenReachedAlready()
    {
        $this->givenInformationRateHasBeenReachedAlready();

        $this->setExpectedException(TwitterRateLimitException::class);

        $this->restApi->getUser($this->userName);
    }

    /**
     * @test
     */
    public function itShouldGetUserInformation()
    {
        $this->givenUserInformationAPICallWillReturnUserInfo();

        $user = $this->restApi->getUser($this->userName);

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
    public function itShouldGetDirectMessagesInMultipleAPICalls()
    {
        $return = [
            $this->minId => $this->thirdDirectMessage,
            $this->stepId  => $this->secondDirectMessage,
            $this->topId => $this->directMessage
        ];

        $this->givenDirectMessageAPICallWillReturnDirectMessages();
        $this->givenSecondDirectMessageAPICallWillReturnOtherDirectMessages();
        $this->givenThirdDirectMessageAPICallWillNotReturnDirectMessages();

        $this->givenAllDirectMessagesAreUnserializable();

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

        $this->givenAllTweetsAreUnserializable();

        $tweets = $this->restApi->getMentionsTweets($this->from, $this->to);

        $this->assertEquals($return, $tweets);
    }

    /**
     * @test
     */
    public function itShouldGetSentTweets()
    {
        $return = [
            $this->minId  => $this->thirdTweet,
            $this->stepId => $this->secondTweet,
            $this->topId  => $this->tweet
        ];

        $this->givenSentTweetAPICallWillReturnTweets();
        $this->givenAllTweetsAreUnserializable();

        $tweets = $this->restApi->getSentTweets($this->userName);

        $this->assertEquals($return, $tweets);
    }

    /**
     * @test
     */
    public function itShouldGetSentDirectMessages()
    {
        $return = [
            $this->minId => $this->thirdDirectMessage,
            $this->stepId  => $this->secondDirectMessage,
            $this->topId => $this->directMessage
        ];

        $this->givenSentDirectMessageAPICallWillReturnDirectMessages();
        $this->givenAllDirectMessagesAreUnserializable();

        $dms = $this->restApi->getSentDirectMessages();

        $this->assertEquals($return, $dms);
    }

    /**
     * @test
     */
    public function itShouldGetFollowedUsers()
    {
        $return = [
            $this->firstFriend,
            $this->secondFriend
        ];

        $this->givenFriendApiCallReturnsFriends();
        $this->givenUserSerializerCanUnserializeFirstFriend();
        $this->givenUserSerializerCanUnserializeSecondFriend();

        $friends = $this->restApi->getFollowedUsers($this->user);

        $this->assertEquals($return, $friends);
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
    public function itShouldDeleteTweet()
    {
        $this->assertApiCallForDeletingTweetWillBeMade();

        $this->restApi->deleteTweet($this->tweet);
    }

    /**
     * @test
     */
    public function itShouldDeleteDirectMessage()
    {
        $this->assertApiCallForDeletingDirectMessageWillBeMade();

        $this->restApi->deleteDirectMessage($this->directMessage);
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
     * @param string $id
     *
     * @return \stdClass
     */
    private function getSerializedFriend($id)
    {
        $friend = new \stdClass();
        $friend->id = $id;

        return $friend;
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

    /**
     * @param string $id
     *
     * @return TwitterUser
     */
    private function getFriend($id)
    {
        /** @var TwitterUser | Mock $user */
        $user = \Mockery::mock(TwitterUser::class);
        $user->shouldReceive('getId')->andReturn($id);

        return $user;
    }

    private function givenInformationRateHasBeenReachedAlready()
    {
        $this->restApi->setRateLimit(
            TwitterClient::GET_USER,
            new LimitedApiRate(
                1,
                0,
                time() + 100
            )
        );
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

    private function givenSentDirectMessageAPICallWillReturnDirectMessages()
    {
        $this->adapter
            ->shouldReceive('sentDirectMessages')
            ->with(\Mockery::on(function (SentDirectMessageQuery $query) {
                return $query == new SentDirectMessageQuery(200);
            }))
            ->andReturn(self::getResponse([
                $this->topId => $this->firstSerializedDirectMessage,
                $this->stepId => $this->secondSerializedDirectMessage,
                $this->minId => $this->thirdSerializedDirectMessage
            ]));
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

    private function givenAllDirectMessagesAreUnserializable()
    {
        $this->givenDirectMessageSerializerCanUnserializeFirstDirectMessage();
        $this->givenDirectMessageSerializerCanUnserializeSecondDirectMessage();
        $this->givenDirectMessageSerializerCanUnserializeThirdDirectMessage();
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

    private function givenSentTweetAPICallWillReturnTweets()
    {
        $this->adapter
            ->shouldReceive('statusesUserTimeLine')
            ->with(\Mockery::on(function (UserTimelineQuery $query) {
                return $query == new UserTimelineQuery(UserIdentifier::fromScreenName($this->userName), 200);
            }))
            ->andReturn(self::getResponse([
                $this->topId => $this->firstSerializedTweet,
                $this->stepId => $this->secondSerializedTweet,
                $this->minId => $this->thirdSerializedTweet
            ]));
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

    private function givenAllTweetsAreUnserializable()
    {
        $this->givenTweetSerializerCanUnserializeFirstTweet();
        $this->givenTweetSerializerCanUnserializeSecondTweet();
        $this->givenTweetSerializerCanUnserializeThirdTweet();
    }

    private function givenNewDirectMessageAPICallSucceeds()
    {
        $this->adapter
            ->shouldReceive('newDirectMessage')
            ->with(\Mockery::on(function (DirectMessageParameters $params) {
                return $params == new DirectMessageParameters(UserIdentifier::fromId($this->userId), $this->message);
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }

    private function givenFriendApiCallReturnsFriends()
    {
        $response = new \stdClass();
        $response->users = [ $this->firstSerializedFriend, $this->secondSerializedFriend ];
        $response->next_cursor = 0;

        $this->adapter
            ->shouldReceive('friends')
            ->with(\Mockery::on(function (FriendsListQuery $query) {
                return $query == new FriendsListQuery(UserIdentifier::fromId($this->userId), 200, -1);
            }))
            ->andReturn(self::getResponse($response))
            ->once();
    }

    private function givenUserSerializerCanUnserializeFirstFriend()
    {
        $this->userSerializer
            ->shouldReceive('unserialize')
            ->with($this->firstSerializedFriend)
            ->andReturn($this->firstFriend);
    }

    private function givenUserSerializerCanUnserializeSecondFriend()
    {
        $this->userSerializer
            ->shouldReceive('unserialize')
            ->with($this->secondSerializedFriend)
            ->andReturn($this->secondFriend);
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

    private function assertApiCallForDeletingTweetWillBeMade()
    {
        $this->adapter
            ->shouldReceive('deleteStatus')
            ->with(\Mockery::on(function (DeleteTweetParameters $params) {
                return $params == new DeleteTweetParameters((string) $this->tweet->getId());
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }

    private function assertApiCallForDeletingDirectMessageWillBeMade()
    {
        $this->adapter
            ->shouldReceive('deleteDirectMessage')
            ->with(\Mockery::on(function (DeleteDirectMessageParameters $params) {
                return $params == new DeleteDirectMessageParameters((string) $this->directMessage->getId());
            }))
            ->andReturn(self::getResponse([]))
            ->once();
    }
}
