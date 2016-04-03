<?php

namespace Twitter\Object;

use Twitter\TwitterSerializable;

class TwitterPlace implements TwitterSerializable
{
    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Static constructor.
     *
     * @return TwitterPlace
     */
    public static function create()
    {
        return new self();
    }
}
