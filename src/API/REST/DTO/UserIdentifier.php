<?php

namespace Twitter\API\REST\DTO;

use Twitter\API\REST\ApiParameters;

class UserIdentifier implements ApiParameters
{
    /** @var int */
    private $id;

    /** @var string */
    private $screenName;

    /**
     * UserIdentifier constructor.
     *
     * @param int    $id
     * @param string $screenName
     */
    private function __construct($id, $screenName)
    {

        $this->id = $id;
        $this->screenName = $screenName;
    }

    /**
     * @param int $id
     *
     * @return UserIdentifier
     */
    public static function fromId($id)
    {
        return new self($id, null);
    }

    /**
     * @param string $screenName
     *
     * @return UserIdentifier
     */
    public static function fromScreenName($screenName)
    {
        return new self(null, $screenName);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if ($this->id !== null) {
            return [
                'user_id' => $this->id
            ];
        }

        return [
            'screen_name' => $this->screenName
        ];
    }
}
