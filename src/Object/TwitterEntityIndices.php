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
     * Constructor.
     */
    public function __construct()
    {
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

    /**
     * Static constructor.
     *
     * @param int $from
     * @param int $to
     *
     * @return TwitterEntityIndices
     */
    public static function create($from, $to)
    {
        $obj = new self();

        $obj->from = $from;
        $obj->to = $to;

        return $obj;
    }
}
