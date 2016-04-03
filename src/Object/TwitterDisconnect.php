<?php

namespace Twitter\Object;

use Twitter\TwitterObject;

class TwitterDisconnect implements TwitterObject
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $streamName;

    /**
     * @var string
     */
    private $reason;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return string
     */
    public function getStreamName()
    {
        return $this->streamName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Disconnect [' . $this->streamName . ']';
    }

    /**
     * Static constructor.
     *
     * @param string $code
     * @param string $reason
     * @param string $streamName
     *
     * @return TwitterDisconnect
     */
    public static function create($code, $streamName, $reason)
    {
        $obj = new self();

        $obj->code = $code;
        $obj->reason = $reason;
        $obj->streamName = $streamName;

        return $obj;
    }
}
