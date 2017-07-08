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
use Twitter\API\REST\Factory\CodebirdFactory;
use Twitter\API\REST\OAuth\AuthenticationToken;
use Twitter\API\REST\Query\DirectMessage\DirectMessageQuery;
use Twitter\API\REST\Query\DirectMessage\SentDirectMessageQuery;
use Twitter\API\REST\Query\Friends\FriendsListQuery;
use Twitter\API\REST\Query\Stream\UserStreamQuery;
use Twitter\API\REST\Query\Tweet\MentionsTimelineQuery;
use Twitter\API\REST\Query\Tweet\UserTimelineQuery;
use Twitter\API\REST\Query\User\UserInformationQuery;
use Twitter\API\REST\Response\ApiRate;
use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\Response\HttpStatus;
use Twitter\API\REST\Response\LimitedApiRate;
use Twitter\API\REST\Response\UnlimitedApiRate;
use Twitter\API\REST\TwitterApiGateway;

class CodebirdTwitterApiGateway implements TwitterApiGateway, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var Codebird */
    private $codebird;

    /**
     * Constructor
     *
     * @param CodebirdFactory $factory
     * @param string          $consumerKey
     * @param string          $consumerSecret
     */
    public function __construct(
        CodebirdFactory $factory,
        $consumerKey,
        $consumerSecret
    ) {
        $this->codebird = $factory->build($consumerKey, $consumerSecret);
        $this->codebird->setReturnFormat('OBJECT');
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
        $reply = $this->handleResult($this->codebird->oauth_requestToken())->getContent();

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
        $reply = $this->handleResult(
            $this->codebird->oauth_accessToken(['oauth_verifier' => $verificationToken])
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
        $this->codebird->user($request->toArray());
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
        return $this->handleResult($this->codebird->users_show($request->toArray()));
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
        return $this->handleResult(
            $this->codebird->statuses_mentionsTimeline($query->toArray()),
            true
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
        return $this->handleResult(
            $this->codebird->statuses_userTimeline($query->toArray()),
            true
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
        return $this->handleResult(
            $this->codebird->directMessages($query->toArray()),
            true
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
        return $this->handleResult(
            $this->codebird->directMessages_sent($query->toArray()),
            true
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
        return $this->handleResult($this->codebird->friends_list($query->toArray()));
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
        return $this->handleResult($this->codebird->statuses_update($parameters->toArray()));
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
        return $this->handleResult($this->codebird->directMessages_new($parameters->toArray()));
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
        return $this->handleResult($this->codebird->statuses_destroy_ID($parameters->toArray()));
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
        return $this->handleResult($this->codebird->directMessages_destroy($parameters->toArray()));
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
        return $this->handleResult($this->codebird->friendships_create($parameters->toArray()));
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
        return $this->handleResult($this->codebird->friendships_destroy($parameters->toArray()));
    }

    /**
     * Handles a twitter API response object
     *
     * @param object $result
     * @param bool   $isList
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    private function handleResult($result, $isList = false)
    {
        $this->handleErrors($result);

        $httpStatus = $this->getHttpStatus($result);
        $rate = $this->getRate($result);
        $content = $this->getContent($result, $isList);

        return new ApiResponse($httpStatus, $content, $rate);
    }

    /**
     * @param object $result
     *
     * @throws TwitterException
     */
    private function handleErrors($result)
    {
        if (isset($result->errors)) {
            $error = reset($result->errors);
            throw new TwitterException($error->message, $error->code);
        }
    }

    /**
     * @param object $result
     *
     * @return HttpStatus
     *
     * @throws TwitterException
     */
    private function getHttpStatus($result)
    {
        $httpStatus = new HttpStatus($result->httpstatus);
        if ($httpStatus->isError()) {
            throw new TwitterException($result->message);
        }

        return $httpStatus;
    }

    /**
     * @param object $result
     *
     * @return ApiRate
     */
    private function getRate($result)
    {
        if (isset($result->rate)) {
            return new LimitedApiRate($result->rate['limit'], $result->rate['remaining'], $result->rate['reset']);
        }

        return new UnlimitedApiRate();
    }

    /**
     * @param object $result
     * @param bool   $isList
     *
     * @return object|array|null
     */
    private function getContent($result, $isList)
    {
        $content = $result;

        unset($content->httpstatus, $content->rate);

        if ($isList) {
            $content = [];
            foreach ($result as $index => $obj) {
                if (is_numeric($index)) {
                    $content[(int) $index] = $obj;
                }
            }
        } elseif (empty($content)) {
            $content = null;
        }

        return $content;
    }
}
