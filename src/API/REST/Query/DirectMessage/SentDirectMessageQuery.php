<?php

namespace Twitter\API\REST\Query\DirectMessage;

use Twitter\API\REST\ApiParameters;

class SentDirectMessageQuery implements ApiParameters
{
    /** @var int */
    private $count;

    /** @var int */
    private $fromId;

    /** @var int */
    private $toId;

    /** @var bool */
    private $includeEntities;

    /** @var int */
    private $page;

    /**
     * MentionsTimelineQuery constructor.
     *
     * @param int  $count
     * @param int  $fromId
     * @param int  $toId
     * @param bool $includeEntities
     * @param int  $page
     */
    public function __construct(
        $count = 20,
        $fromId = null,
        $toId = null,
        $includeEntities = true,
        $page = 1
    ) {
        $this->count = $count;
        $this->fromId = $fromId;
        $this->toId = $toId;
        $this->includeEntities = $includeEntities;
        $this->page = $page;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $request = [
            'include_entities' => $this->includeEntities ? 'true' : 'false',
            'page' => $this->page
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
