<?php

namespace Twitter\API\REST;

use Twitter\API\Exception\TwitterException;
use Twitter\Object\Tweet;
use Twitter\Object\TwitterDirectMessage;
use Twitter\Object\TwitterUser;

interface TwitterClient
{
    const GET_USER = 'get-user';
    const GET_MENTIONS = 'get-mentions';
    const GET_DIRECT_MESSAGES = 'get-direct-messages';
    const GET_SENT_TWEETS = 'get-sent-tweets';
    const GET_SENT_DIRECT_MESSAGES = 'get-sent-direct-messages';
    const GET_FOLLOWED_USERS = 'get-followed-users';

    const SEND_TWEET = 'send-tweet';
    const SEND_DIRECT_MESSAGE = 'send-direct-message';

    const DELETE_TWEET = 'delete-tweet';
    const DELETE_DIRECT_MESSAGE = 'delete-direct-message';

    const FOLLOW = 'follow';
    const UNFOLLOW = 'unfollow';

    /**
     * @param string $userName
     *
     * @return TwitterUser
     *
     * @throws TwitterException
     */
    public function getUser($userName);

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
    public function getMentionsTweets($from = null, $to = null);

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
    public function getDirectMessages($from = null, $to = null);

    /**
     * @param string $userName
     *
     * @return Tweet[]
     *
     * @throws TwitterException
     */
    public function getSentTweets($userName);

    /**
     * @return TwitterDirectMessage[]
     *
     * @throws TwitterException
     */
    public function getSentDirectMessages();

    /**
     * @param TwitterUser $user
     *
     * @return TwitterUser[]
     *
     * @throws TwitterException
     */
    public function getFollowedUsers(TwitterUser $user);

    /**
     * Sends a tweet
     *
     * @param  string $message
     * @param  Tweet $replyTo
     *
     * @throws TwitterException
     */
    public function sendTweet($message, Tweet $replyTo = null);

    /**
     * Sends a direct message to $user
     *
     * @param  TwitterUser $user
     * @param  string      $message
     *
     * @throws TwitterException
     */
    public function sendDirectMessage(TwitterUser $user, $message);

    /**
     * @param Tweet $tweet
     *
     * @throws TwitterException
     */
    public function deleteTweet(Tweet $tweet);

    /**
     * @param TwitterDirectMessage $directMessage
     *
     * @throws TwitterException
     */
    public function deleteDirectMessage(TwitterDirectMessage $directMessage);

    /**
     * Follow a $user
     *
     * @param  TwitterUser $user
     *
     * @throws TwitterException
     */
    public function follow(TwitterUser $user);

    /**
     * Unfollow a $user
     *
     * @param  TwitterUser $user
     *
     * @throws TwitterException
     */
    public function unfollow(TwitterUser $user);
}
