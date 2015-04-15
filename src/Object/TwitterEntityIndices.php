<?php
namespace Twitter\Object;

use Twitter\TwitterSerializable;

class TwitterEntityIndices implements TwitterSerializable
{

    /**
     * @var int
     */
    private $from;

    /**
     * @var int
     */
    private $to;

    /**
     * Constructor
     *
     * @param int $from
     * @param int $to
     */
    function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return int
     */
    public function getTo()
    {
        return $this->to;
    }
} 