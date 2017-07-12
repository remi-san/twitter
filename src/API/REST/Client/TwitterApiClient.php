<?php

namespace Twitter\API\REST\Client;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Twitter\API\REST\TwitterApiGateway;
use Twitter\API\REST\TwitterClient;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterUser;
use Twitter\Serializer\TweetSerializer;
use Twitter\Serializer\TwitterDirectMessageSerializer;
use Twitter\Serializer\TwitterUserSerializer;
use Twitter\API\Exception\TwitterException;
use Twitter\API\Exception\TwitterRateLimitException;
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
use Twitter\API\REST\Query\User\UserInformationQuery;
use Twitter\API\REST\Response\ApiRate;
use Twitter\API\REST\Response\ApiResponse;

class TwitterApiClient implements TwitterClient, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var TwitterApiGateway */
    private $adapter;

    /** @var TwitterUserSerializer */
    private $userSerializer;

    /** @var TweetSerializer */
    private $tweetSerializer;

    /** @var TwitterDirectMessageSerializer */
    private $directMessageSerializer;

    /** @var ApiRate[] */
    private $rateLimits;

    /**
     * Constructor
     *
     * @param TwitterApiGateway              $adapter
     * @param TwitterUserSerializer          $userSerializer
     * @param TweetSerializer                $tweetSerializer
     * @param TwitterDirectMessageSerializer $directMessageSerializer
     */
    public function __construct(
        TwitterApiGateway $adapter,
        TwitterUserSerializer $userSerializer,
        TweetSerializer $tweetSerializer,
        TwitterDirectMessageSerializer  $directMessageSerializer
    ) {
        $this->adapter = $adapter;

        $this->tweetSerializer = $tweetSerializer;
        $this->directMessageSerializer = $directMessageSerializer;
        $this->userSerializer = $userSerializer;

        $this->rateLimits = [];

        $this->logger = new NullLogger();
    }

    /**
     * @param string $userName
     *
     * @return TwitterUser
     *
     * @throws TwitterException
     */
    public function getUser($userName)
    {
        $this->checkRate(self::GET_USER);

        $response = $this->adapter->getUserInformation(
            new UserInformationQuery(
                UserIdentifier::fromScreenName($userName),
                true
            )
        );

        $this->handleResponse(self::GET_USER, $response);

        return $this->userSerializer->unserialize($response->getContent());
    }

    /**
     * Gets the mention tweets with id between $from and $to
     *
     * @param  string $from
     * @param  string $to
     *
     * @return Tweet[]
     *
     * @throws TwitterException
     */
    public function getMentionsTweets($from = null, $to = null)
    {
        $this->logger->info('Retrieving tweets messages from ' . $from . ' to ' . $to);

        $this->checkRate(self::GET_MENTIONS);

        $tweets = [];
        $response = $this->adapter->statusesMentionsTimeLine(new MentionsTimelineQuery(200, $from, $to, true));

        $this->handleResponse(self::GET_MENTIONS, $response);

        foreach ($response->getContent() as $index => $obj) {
            $tweet = $this->tweetSerializer->unserialize($obj);
            $id = (string) $tweet->getId();
            $tweets[(int) $id] = $tweet;
        }

        if (!empty($tweets) && $from !== null) {
            $moreTweets = $this->getMentionsTweets($from, min(array_keys($tweets))-1);
            if (!empty($moreTweets)) {
                $tweets += $moreTweets;
            }
        }
        ksort($tweets);
        return $tweets;
    }

    /**
     * Gets the direct messages with id between $from and $to
     *
     * @param  string $from
     * @param  string $to
     *
     * @return TwitterDirectMessage[]
     *
     * @throws TwitterException
     */
    public function getDirectMessages($from = null, $to = null)
    {
        $this->logger->info('Retrieving direct messages from ' . $from . ' to ' . $to);

        $this->checkRate(self::GET_DIRECT_MESSAGES);

        $dms = [];
        $response = $this->adapter->directMessages(new DirectMessageQuery(200, $from, $to, true));

        $this->handleResponse(self::GET_DIRECT_MESSAGES, $response);

        foreach ($response->getContent() as $index => $obj) {
            $dm = $this->directMessageSerializer->unserialize($obj);
            $id = (string) $dm->getId();
            $dms[(int) $id] = $dm;
        }

        if (!empty($dms) && $from !== null) {
            $moreDms = $this->getDirectMessages($from, min(array_keys($dms))-1);
            if (!empty($moreDms)) {
                $dms += $moreDms;
            }
        }
        ksort($dms);
        return $dms;
    }

    /**
     * @param string $userName
     *
     * @return Tweet[]
     *
     * @throws TwitterException
     */
    public function getSentTweets($userName)
    {
        $this->checkRate(self::GET_SENT_TWEETS);

        $response = $this->adapter->statusesUserTimeLine(
            new UserTimelineQuery(
                UserIdentifier::fromScreenName($userName),
                200
            )
        );

        $this->handleResponse(self::GET_SENT_TWEETS, $response);

        $tweets = [];
        foreach ($response->getContent() as $index => $obj) {
            $tweet = $this->tweetSerializer->unserialize($obj);
            $id = (string) $tweet->getId();
            $tweets[(int) $id] = $tweet;
        }
        ksort($tweets);
        return $tweets;
    }

    /**
     * @return TwitterDirectMessage[]
     *
     * @throws TwitterException
     */
    public function getSentDirectMessages()
    {
        $this->checkRate(self::GET_SENT_DIRECT_MESSAGES);

        $response = $this->adapter->sentDirectMessages(new SentDirectMessageQuery(200));

        $this->handleResponse(self::GET_SENT_DIRECT_MESSAGES, $response);

        $dms = [];
        foreach ($response->getContent() as $index => $obj) {
            $dm = $this->directMessageSerializer->unserialize($obj);
            $id = (string) $dm->getId();
            $dms[(int) $id] = $dm;
        }
        ksort($dms);
        return $dms;
    }

    /**
     * @param TwitterUser $user
     *
     * @return TwitterUser[]
     *
     * @throws TwitterException
     */
    public function getFollowedUsers(TwitterUser $user)
    {
        $cursor = -1;
        $serializedUsers = [];

        while ($cursor !== 0) {
            $this->checkRate(self::GET_FOLLOWED_USERS);

            $response = $this->adapter->friends(
                new FriendsListQuery(
                    UserIdentifier::fromId($user->getId()),
                    200,
                    $cursor
                )
            );

            $this->handleResponse(self::GET_FOLLOWED_USERS, $response);

            $friendsResponse = $response->getContent();
            $serializedUsers = array_merge($serializedUsers, $friendsResponse->users);

            $cursor = $friendsResponse->next_cursor;
        }

        return array_map(function ($serializedUser) {
            return $this->userSerializer->unserialize($serializedUser);
        }, $serializedUsers);
    }

    /**
     * Sends a tweet
     *
     * @param  string $message
     * @param  Tweet $replyTo
     *
     * @throws TwitterException
     */
    public function sendTweet($message, Tweet $replyTo = null)
    {
        $this->checkRate(self::SEND_TWEET);

        $params =  new TweetParameters(
            $message,
            $replyTo !== null ? (int) ((string) $replyTo->getId()) : null
        );

        $response = $this->adapter->updateStatus($params);

        $this->handleResponse(self::SEND_TWEET, $response);
    }

    /**
     * Sends a direct message to $user
     *
     * @param  TwitterUser $user
     * @param  string      $message
     *
     * @throws TwitterException
     */
    public function sendDirectMessage(TwitterUser $user, $message)
    {
        $this->checkRate(self::SEND_DIRECT_MESSAGE);

        $params =  new DirectMessageParameters(
            UserIdentifier::fromId($user->getId()),
            $message
        );

        $response = $this->adapter->newDirectMessage($params);

        $this->handleResponse(self::SEND_DIRECT_MESSAGE, $response);
    }

    /**
     * @param Tweet $tweet
     *
     * @throws TwitterException
     */
    public function deleteTweet(Tweet $tweet)
    {
        $this->checkRate(self::DELETE_TWEET);

        $params = new DeleteTweetParameters((string) $tweet->getId());

        $response = $this->adapter->deleteStatus($params);

        $this->handleResponse(self::DELETE_TWEET, $response);
    }

    /**
     * @param TwitterDirectMessage $directMessage
     *
     * @throws TwitterException
     */
    public function deleteDirectMessage(TwitterDirectMessage $directMessage)
    {
        $this->checkRate(self::DELETE_DIRECT_MESSAGE);

        $params = new DeleteDirectMessageParameters((string) $directMessage->getId());

        $response = $this->adapter->deleteDirectMessage($params);

        $this->handleResponse(self::DELETE_DIRECT_MESSAGE, $response);
    }

    /**
     * Follow a $user
     *
     * @param  TwitterUser $user
     *
     * @throws TwitterException
     */
    public function follow(TwitterUser $user)
    {
        $this->checkRate(self::FOLLOW);

        $params =  new FollowParameters(UserIdentifier::fromId($user->getId()));

        $response = $this->adapter->createFriendship($params);

        $this->handleResponse(self::FOLLOW, $response);
    }

    /**
     * Unfollow a $user
     *
     * @param  TwitterUser $user
     *
     * @throws TwitterException
     */
    public function unfollow(TwitterUser $user)
    {
        $this->checkRate(self::UNFOLLOW);

        $params = UserIdentifier::fromId($user->getId());

        $response = $this->adapter->destroyFriendship($params);

        $this->handleResponse(self::UNFOLLOW, $response);
    }

    /**
     * @param string  $category
     * @param ApiRate $rate
     */
    public function setRateLimit($category, ApiRate $rate)
    {
        $this->rateLimits[$category] = $rate;
    }

    /**
     * @param string $category
     *
     * @throws TwitterRateLimitException
     */
    private function checkRate($category)
    {
        if (!array_key_exists($category, $this->rateLimits)) {
            return;
        }

        $rate = $this->rateLimits[$category];
        if (!$rate->canMakeAnotherCall()) {
            throw TwitterRateLimitException::create($category, $rate);
        }
    }

    /**
     * @param string      $category
     * @param ApiResponse $response
     */
    private function handleResponse($category, ApiResponse $response)
    {
        $this->setRateLimit($category, $response->getRate());
    }
}
