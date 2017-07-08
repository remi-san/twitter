<?php

namespace Twitter\API\REST\Response;

interface ApiRate
{
    /**
     * @return int
     */
    public function getLimit();

    /**
     * @return bool
     */
    public function canMakeAnotherCall();

    /**
     * @return \DateTimeInterface
     */
    public function nextWindow();
}
