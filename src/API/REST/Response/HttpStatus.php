<?php

namespace Twitter\API\REST\Response;

class HttpStatus
{
    /** @var int */
    private $status;

    /**
     * HttpStatus constructor.
     *
     * @param int $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->status !== 200;
    }
}
