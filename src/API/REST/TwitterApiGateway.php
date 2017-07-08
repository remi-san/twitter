<?php

namespace Twitter\API\REST;

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

interface TwitterApiGateway
{
    /**
     * Authenticate a user
     *
     * @param AuthenticationToken $token
     */
    public function authenticate(AuthenticationToken $token);

    /**
     * Get Oauth authentication URL
     *
     * @return string
     *
     * @throws TwitterException
     */
    public function getAuthenticationUrl();

    /**
     * Get the authentication token by providing the verifier.
     *
     * @param string $verificationToken
     *
     * @return AuthenticationToken
     *
     * @throws TwitterException
     */
    public function getAuthenticationToken($verificationToken);

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
    );

    /**
     * @param UserInformationQuery $request
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function getUserInformation(UserInformationQuery $request);

    /**
     * Get the tweets mentioning the user
     *
     * @param MentionsTimelineQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function statusesMentionsTimeLine(MentionsTimelineQuery $query);

    /**
     * Get the tweets of the user
     *
     * @param UserTimelineQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function statusesUserTimeLine(UserTimelineQuery $query);

    /**
     * Get the direct messages to teh user
     *
     * @param DirectMessageQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function directMessages(DirectMessageQuery $query);

    /**
     * Get the direct messages sent by the user
     *
     * @param SentDirectMessageQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function sentDirectMessages(SentDirectMessageQuery $query);

    /**
     * @param FriendsListQuery $query
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function friends(FriendsListQuery $query);

    /**
     * Sends a tweet
     *
     * @param TweetParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function updateStatus(TweetParameters $parameters);

    /**
     * Sends a direct message to $user
     *
     * @param DirectMessageParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function newDirectMessage(DirectMessageParameters $parameters);

    /**
     * Delete a tweet
     *
     * @param DeleteTweetParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function deleteStatus(DeleteTweetParameters $parameters);

    /**
     * Delete a direct message
     *
     * @param DeleteDirectMessageParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function deleteDirectMessage(DeleteDirectMessageParameters $parameters);

    /**
     * Follow a $user
     *
     * @param FollowParameters $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function createFriendship(FollowParameters $parameters);

    /**
     * Unfollow a $user
     *
     * @param UserIdentifier $parameters
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function destroyFriendship(UserIdentifier $parameters);
}
