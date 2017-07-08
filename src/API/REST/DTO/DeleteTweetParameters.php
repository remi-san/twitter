<?php

namespace Twitter\API\REST\DTO;

use Twitter\API\REST\ApiParameters;

class DeleteTweetParameters implements ApiParameters
{
    /** @var int */
    private $id;

    /** @var bool */
    private $trimUser;

    /**
     * DeleteTweetParameters constructor.
     *
     * @param int  $id
     * @param bool $trimUser
     */
    public function __construct($id, $trimUser = false)
    {
        $this->id = $id;
        $this->trimUser = $trimUser;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'trim_user' => $this->trimUser ? 'true' : 'false'
        ];
    }
}
