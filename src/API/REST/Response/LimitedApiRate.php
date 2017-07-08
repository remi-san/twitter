<?php

namespace Twitter\API\REST\Response;

class LimitedApiRate implements ApiRate
{
    /** @var int */
    private $limit;

    /** @var int */
    private $remaining;

    /** @var \DateTimeInterface */
    private $reset;

    /**
     * LimitedApiRate constructor.
     *
     * @param int $limit
     * @param int $remaining
     * @param int $reset
     */
    public function __construct(
        $limit,
        $remaining,
        $reset
    ) {
        $this->limit = $limit;
        $this->remaining = $remaining;
        $this->reset = \DateTimeImmutable::createFromFormat(
            'U',
            $reset,
            new \DateTimeZone('UTC')
        );
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function canMakeAnotherCall()
    {
        return $this->remaining > 0;
    }

    /**
     * @return \DateTimeInterface
     */
    public function nextWindow()
    {
        return $this->reset;
    }
}
