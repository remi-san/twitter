<?php

namespace Twitter\API\REST\Query\Friends;

use Twitter\API\REST\ApiParameters;
use Twitter\API\REST\DTO\UserIdentifier;

class FriendsListQuery implements ApiParameters
{
    /** @var UserIdentifier */
    private $userIdentifier;

    /** @var int */
    private $count;

    /** @var int */
    private $cursor;

    /** @var bool */
    private $skipStatus;

    /** @var boolean */
    private $includeUserEntities;

    /**
     * UserInformationQuery constructor.
     *
     * @param UserIdentifier $userIdentifier
     * @param int            $count
     * @param int            $cursor
     * @param bool           $skipStatus
     * @param bool           $includeUserEntities
     */
    public function __construct(
        UserIdentifier $userIdentifier,
        $count = 20,
        $cursor = -1,
        $skipStatus = true,
        $includeUserEntities = false
    ) {
        $this->userIdentifier = $userIdentifier;
        $this->count = $count;
        $this->cursor = $cursor;
        $this->skipStatus = $skipStatus;
        $this->includeUserEntities = $includeUserEntities;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $request = $this->userIdentifier->toArray();

        $request['count'] = $this->count;
        $request['cursor'] = $this->cursor;
        $request['skip_status'] = $this->skipStatus ? 'true' : 'false';
        $request['include_entities'] = $this->includeUserEntities ? 'true' : 'false';

        return $request;
    }
}
