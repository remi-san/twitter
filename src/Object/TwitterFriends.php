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
     * Constructor
     *
     * @param int[] $friends
     */
    function __construct(array $friends = array())
    {
        $this->friends = $friends;
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
    public function __toString() {
        return 'Friends List';
    }
} 