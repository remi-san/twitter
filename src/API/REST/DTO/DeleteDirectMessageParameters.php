<?php

namespace Twitter\API\REST\DTO;

use Twitter\API\REST\ApiParameters;

class DeleteDirectMessageParameters implements ApiParameters
{
    /** @var int */
    private $id;

    /** @var bool */
    private $includeEntities;

    /**
     * DeleteTweetParameters constructor.
     *
     * @param int  $id
     * @param bool $includeEntities
     */
    public function __construct($id, $includeEntities = true)
    {
        $this->id = $id;
        $this->includeEntities = $includeEntities;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'include_entities' => $this->includeEntities ? 'true' : 'false'
        ];
    }
}
