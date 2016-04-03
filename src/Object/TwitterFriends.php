<?php

namespace Twitter\Object;

use Twitter\TwitterObject;

class TwitterFriends implements TwitterObject
{
    /**
     * @var int[]
     */
    private $friends;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return \int[]
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Friends List';
    }

    /**
     * Static constructor.
     *
     * @param int[] $friends
     *
     * @return TwitterFriends
     */
    public static function create(array $friends = [])
    {
        $obj = new self();

        $obj->friends = $friends;

        return $obj;
    }
}
