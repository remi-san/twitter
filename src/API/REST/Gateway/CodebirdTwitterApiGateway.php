<?php

namespace Twitter\API\REST\Gateway;

use Codebird\Codebird;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Twitter\API\Exception\TwitterException;
use Twitter\API\REST\DTO\DeleteDirectMessageParameters;
use Twitter\API\REST\DTO\DeleteTweetParameters;
use Twitter\API\REST\DTO\DirectMessageParameters;
use Twitter\API\REST\DTO\FollowParameters;
use Twitter\API\REST\DTO\TweetParameters;
use Twitter\API\REST\DTO\UserIdentifier;
use Twitter\API\REST\OAuth\AuthenticationToken;
use Twitter\API\REST\Query\DirectMessage\DirectMessageQuery;
use Twitter\API\REST\Query\DirectMessage\SentDirectMessageQuery;
use Twitter\API\REST\Query\Friends\FriendsListQuery;
use Twitter\API\REST\Query\Stream\UserStreamQuery;
use Twitter\API\REST\Query\Tweet\MentionsTimelineQuery;
use Twitter\API\REST\Query\Tweet\UserTimelineQuery;
use Twitter\API\REST\Query\User\UserInformationQuery;
use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\TwitterApiGateway;

class CodebirdTwitterApiGateway implements TwitterApiGateway, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var Codebird */
    private $codebird;

    /** @var CodebirdResponseParser */
    private $responseParser;

    /**
     * Constructor
     *
     * @param Codebird               $codebird
     * @param CodebirdResponseParser $responseParser
     */
    public function __construct(
        Codebird $codebird,
        CodebirdResponseParser $responseParser
    ) {
        $this->codebird = $codebird;
        $this->codebird->setReturnFormat('OBJECT');

        $this->responseParser = $responseParser;
    }

    /**
     * Authenticate a user
     *
     * @param AuthenticationToken $token
     */
    public function authenticate(AuthenticationToken $token)
    {
        $this->codebird->setToken($token->getToken(), $token->getSecret());
    }

    /**
     * Get Oauth authentication URL
     *
     * @return string
     *
     * @throws TwitterException
     */
    public function getAuthenticationUrl()
    {
        $reply = $this->responseParser->parseObject(
            $this->callApi('oauth_requestToken')
        )->getContent();

        $this->authenticate(new AuthenticationToken($reply->oauth_token, $reply->oauth_token_secret));

        return $this->codebird->oauth_authorize();
    }

    /**
     * Get the authentication token by providing the verifier.
     *
     * @param string $verificationToken
     *
     * @return AuthenticationToken
     *
     * @throws TwitterException
     */
    public function getAuthenticationToken($verificationToken)
    {
        $reply = $this->responseParser->parseObject(
            $this->callApi('oauth_accessToken', ['oauth_verifier' => $verificationToken])
        )->getContent();

        return new AuthenticationToken($reply->oauth_token, $reply->oauth_token_secret);
    }

    /**
     * Sets streaming callback
     *
     * @param UserStreamQuery $request
     * @param callable        $callback
     *
     * @throws \Exception
     */
    public function consumeUserStream(
        UserStreamQuery $request,
        callable $callback
    ) {
        $this->codebird->setStreamingCallback($callback);
        $this->callApi('user', $request->toArray());
    }

    /**
     * @param UserInformationQuery $request
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function getUserInformation(UserInformationQuery $request)
    {
        return $this->responseParser->parseObject(
            $this->callApi('users_show', $request->toArray())
        );
    }

    /**
     * Get the tweets mentioning the user
     *
     * @param MentionsTimelineQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function statusesMentionsTimeLine(MentionsTimelineQuery $query)
    {
        return $this->responseParser->parseList(
            $this->callApi('statuses_mentionsTimeline', $query->toArray())
        );
    }

    /**
     * Get the tweets of the user
     *
     * @param UserTimelineQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function statusesUserTimeLine(UserTimelineQuery $query)
    {
        return $this->responseParser->parseList(
            $this->callApi('statuses_userTimeline', $query->toArray())
        );
    }

    /**
     * Get the direct messages to teh user
     *
     * @param DirectMessageQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function directMessages(DirectMessageQuery $query)
    {
        return $this->responseParser->parseList(
            $this->callApi('directMessages', $query->toArray())
        );
    }

    /**
     * Get the direct messages sent by the user
     *
     * @param SentDirectMessageQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function sentDirectMessages(SentDirectMessageQuery $query)
    {
        return $this->responseParser->parseList(
            $this->callApi('directMessages_sent', $query->toArray())
        );
    }

    /**
     * @param FriendsListQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function friends(FriendsListQuery $query)
    {
        return $this->responseParser->parseObject(
            $this->callApi('friends_list', $query->toArray())
        );
    }

    /**
     * Sends a tweet
     *
     * @param TweetParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function updateStatus(TweetParameters $parameters)
    {
        return $this->responseParser->parseObject(
            $this->callApi('statuses_update', $parameters->toArray())
        );
    }

    /**
     * Sends a direct message to $user
     *
     * @param DirectMessageParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function newDirectMessage(DirectMessageParameters $parameters)
    {
        return $this->responseParser->parseObject(
            $this->callApi('directMessages_new', $parameters->toArray())
        );
    }

    /**
     * Delete a tweet
     *
     * @param DeleteTweetParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function deleteStatus(DeleteTweetParameters $parameters)
    {
        return $this->responseParser->parseObject(
            $this->callApi('statuses_destroy_ID', $parameters->toArray())
        );
    }

    /**
     * Delete a direct message
     *
     * @param DeleteDirectMessageParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function deleteDirectMessage(DeleteDirectMessageParameters $parameters)
    {
        return $this->responseParser->parseObject(
            $this->callApi('directMessages_destroy', $parameters->toArray())
        );
    }

    /**
     * Follow a $user
     *
     * @param FollowParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function createFriendship(FollowParameters $parameters)
    {
        return $this->responseParser->parseObject(
            $this->callApi('friendships_create', $parameters->toArray())
        );
    }

    /**
     * Unfollow a $user
     *
     * @param UserIdentifier $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function destroyFriendship(UserIdentifier $parameters)
    {
        return $this->responseParser->parseObject(
            $this->callApi('friendships_destroy', $parameters->toArray())
        );
    }

    /**
     * Call the twitter API
     *
     * @param string   $slugifiedRoute
     * @param string[] $parameters
     *
     * @return object|array
     */
    private function callApi($slugifiedRoute, array $parameters = [])
    {
        return $this->codebird->{$slugifiedRoute}($parameters);
    }
}
