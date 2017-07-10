<?php

namespace Twitter\API\REST\Response;

class UnlimitedApiRate implements ApiRate
{
    /**
     * @return int
     */
    public function getLimit()
    {
        return -1;
    }

    /**
     * @return bool
     */
    public function canMakeAnotherCall()
    {
        return true;
    }

    /**
     * @return \DateTimeInterface
     */
    public function nextWindow()
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }
}
