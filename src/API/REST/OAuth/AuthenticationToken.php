<?php

namespace Twitter\API\REST\OAuth;

class AuthenticationToken
{
    /** @var string */
    private $token;

    /** @var string */
    private $secret;

    /**
     * AuthenticationToken constructor.
     *
     * @param string $token
     * @param string $secret
     */
    public function __construct($token, $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
}
