<?php

namespace Twitter\API\REST\Query\DirectMessage;

use Twitter\API\REST\ApiParameters;

class DirectMessageQuery implements ApiParameters
{
    /** @var int */
    private $count;

    /** @var int */
    private $fromId;

    /** @var int */
    private $toId;

    /** @var bool */
    private $includeEntities;

    /** @var bool */
    private $skipStatus;

    /**
     * MentionsTimelineQuery constructor.
     *
     * @param int  $count
     * @param int  $fromId
     * @param int  $toId
     * @param bool $includeEntities
     * @param bool $skipStatus
     */
    public function __construct(
        $count = 20,
        $fromId = null,
        $toId = null,
        $includeEntities = true,
        $skipStatus = false
    ) {
        $this->count = $count;
        $this->fromId = $fromId;
        $this->toId = $toId;
        $this->includeEntities = $includeEntities;
        $this->skipStatus = $skipStatus;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $request = [
            'include_entities' => $this->includeEntities ? 'true' : 'false',
            'skip_status' => $this->skipStatus ? 'true' : 'false'
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
