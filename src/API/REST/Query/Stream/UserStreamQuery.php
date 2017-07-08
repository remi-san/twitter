<?php

namespace Twitter\API\REST\Query\Stream;

use Twitter\API\REST\ApiParameters;

class UserStreamQuery implements ApiParameters
{
    /** @var bool */
    private $limitToUser;

    /** @var bool */
    private $includeReplies;

    /**
     * UserStreamQuery constructor.
     *
     * @param bool $limitToUser
     * @param bool $includeReplies
     */
    public function __construct($limitToUser = false, $includeReplies = false)
    {
        $this->limitToUser = $limitToUser;
        $this->includeReplies = $includeReplies;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $request = [];

        if ($this->limitToUser) {
            $request['with'] = 'user';
        }

        if ($this->includeReplies) {
            $request['replies'] = 'all';
        }

        return $request;
    }
}
