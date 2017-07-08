<?php

namespace Twitter\API\REST\Response;

class ApiResponse
{
    /** @var HttpStatus */
    private $httpStatus;

    /** @var ApiRate */
    private $rate;

    /** @var mixed */
    private $content;

    /**
     * ApiResponse constructor.
     *
     * @param HttpStatus $httpStatus
     * @param mixed      $content
     * @param ApiRate    $rate
     */
    public function __construct(
        HttpStatus $httpStatus,
        $content,
        ApiRate $rate
    ) {
        $this->httpStatus = $httpStatus;
        $this->content = $content;
        $this->rate = $rate;
    }

    /**
     * @return HttpStatus
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * @return array|object|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return ApiRate
     */
    public function getRate()
    {
        return $this->rate;
    }
}
