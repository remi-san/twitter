<?php

namespace Twitter\API\Exception;

use Twitter\API\REST\Response\ApiRate;

class TwitterRateLimitException extends TwitterException
{
    /** @var string */
    private $category;

    /** @var ApiRate */
    private $rate;

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return \DateTimeInterface
     */
    public function nextWindow()
    {
        return $this->rate->nextWindow();
    }

    /**
     * @param string  $category
     * @param ApiRate $rate
     *
     * @return TwitterRateLimitException
     */
    public static function create($category, ApiRate $rate)
    {
        $exception = new self('You have reached rate limit. You cannot make an API call yet.');
        $exception->category = $category;
        $exception->rate = $rate;

        return $exception;
    }
}
