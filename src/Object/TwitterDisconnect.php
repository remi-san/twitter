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
     * Constructor
     *
     * @param string $code
     * @param string $reason
     * @param string $streamName
     */
    public function __construct($code, $streamName, $reason)
    {
        $this->code = $code;
        $this->reason = $reason;
        $this->streamName = $streamName;
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
        return 'Disconnect ['.$this->streamName.']';
    }
}
