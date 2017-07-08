<?php

namespace Twitter\API\REST\DTO;

use Twitter\API\REST\ApiParameters;

class FollowParameters implements ApiParameters
{
    /** @var UserIdentifier */
    private $userIdentifier;

    /** @var bool */
    private $follow;

    /**
     * FollowParameters constructor.
     *
     * @param UserIdentifier $userIdentifier
     * @param bool           $follow
     */
    public function __construct(
        UserIdentifier $userIdentifier,
        $follow = false
    ) {
        $this->userIdentifier = $userIdentifier;
        $this->follow = $follow;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $parameters = $this->userIdentifier->toArray();

        $parameters['follow'] = $this->follow ? 'true' : 'false';

        return $parameters;
    }
}
