<?php

namespace Twitter\API\REST\DTO;

use Twitter\API\REST\ApiParameters;

class DirectMessageParameters implements ApiParameters
{
    /** @var UserIdentifier */
    private $userIdentifier;

    /** @var string */
    private $message;

    /**
     * DirectMessageParameters constructor.
     *
     * @param UserIdentifier $userIdentifier
     * @param string         $message
     */
    public function __construct(UserIdentifier $userIdentifier, $message)
    {
        $this->userIdentifier = $userIdentifier;
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $parameters = $this->userIdentifier->toArray();

        $parameters['text'] = $this->message;

        return $parameters;
    }
}
