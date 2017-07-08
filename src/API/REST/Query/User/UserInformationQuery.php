<?php

namespace Twitter\API\REST\Query\User;

use Twitter\API\REST\ApiParameters;
use Twitter\API\REST\DTO\UserIdentifier;

class UserInformationQuery implements ApiParameters
{
    /** @var UserIdentifier */
    private $userIdentifier;

    /** @var boolean */
    private $includeEntities;

    /**
     * UserInformationQuery constructor.
     *
     * @param UserIdentifier $userIdentifier
     * @param bool           $includeEntities
     */
    public function __construct(UserIdentifier $userIdentifier, $includeEntities = false)
    {
        $this->userIdentifier = $userIdentifier;
        $this->includeEntities = $includeEntities;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $request = $this->userIdentifier->toArray();

        $request['include_entities'] = $this->includeEntities ? 'true' : 'false';

        return $request;
    }
}
