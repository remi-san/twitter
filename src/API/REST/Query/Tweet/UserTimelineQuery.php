<?php

namespace Twitter\API\REST\Query\Tweet;

use Twitter\API\REST\ApiParameters;
use Twitter\API\REST\DTO\UserIdentifier;

class UserTimelineQuery implements ApiParameters
{
    /** @var UserIdentifier */
    private $userIdentifier;

    /** @var int */
    private $count;

    /** @var int */
    private $fromId;

    /** @var int */
    private $toId;

    /** @var bool */
    private $includeRetweets;

    /** @var bool */
    private $trimUser;

    /** @var bool */
    private $excludeReplies;

    /**
     * MentionsTimelineQuery constructor.
     *
     * @param UserIdentifier $userIdentifier
     * @param int            $count
     * @param int            $fromId
     * @param int            $toId
     * @param bool           $includeRetweets
     * @param bool           $trimUser
     * @param bool           $excludeReplies
     */
    public function __construct(
        UserIdentifier $userIdentifier,
        $count = 20,
        $fromId = null,
        $toId = null,
        $includeRetweets = true,
        $trimUser = false,
        $excludeReplies = true
    ) {
        $this->userIdentifier = $userIdentifier;
        $this->count = $count;
        $this->fromId = $fromId;
        $this->toId = $toId;
        $this->includeRetweets = $includeRetweets;
        $this->trimUser = $trimUser;
        $this->excludeReplies = $excludeReplies;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $request = $this->userIdentifier->toArray();

        $request['include_rts'] = $this->includeRetweets ? 'true' : 'false';
        $request['trim_user'] = $this->trimUser ? 'true' : 'false';
        $request['exclude_replies'] = $this->excludeReplies ? 'true' : 'false';

        if ($this->count !== null) {
            $request['count'] = $this->count;
        }

        if ($this->fromId !== null) {
            $request['since_id'] = $this->fromId;
        }

        if ($this->toId !== null) {
            $request['max_id'] = $this->toId;
        }

        return $request;
    }
}
