<?php

namespace Twitter\API\REST\Query\Tweet;

use Twitter\API\REST\ApiParameters;

class MentionsTimelineQuery implements ApiParameters
{
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
    private $includeEntities;

    /**
     * MentionsTimelineQuery constructor.
     *
     * @param int  $count
     * @param int  $fromId
     * @param int  $toId
     * @param bool $includeRetweets
     * @param bool $trimUser
     * @param bool $includeEntities
     */
    public function __construct(
        $count = 20,
        $fromId = null,
        $toId = null,
        $includeRetweets = true,
        $trimUser = false,
        $includeEntities = true
    ) {
        $this->count = $count;
        $this->fromId = $fromId;
        $this->toId = $toId;
        $this->includeRetweets = $includeRetweets;
        $this->trimUser = $trimUser;
        $this->includeEntities = $includeEntities;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $request = [
            'include_rts' => $this->includeRetweets ? 'true' : 'false',
            'trim_user' => $this->trimUser ? 'true' : 'false',
            'include_entities' => $this->includeEntities ? 'true' : 'false'
        ];

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
