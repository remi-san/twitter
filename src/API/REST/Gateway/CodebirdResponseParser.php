<?php

namespace Twitter\API\REST\Gateway;

use Twitter\API\Exception\TwitterException;
use Twitter\API\REST\Response\ApiRate;
use Twitter\API\REST\Response\ApiResponse;
use Twitter\API\REST\Response\HttpStatus;
use Twitter\API\REST\Response\LimitedApiRate;
use Twitter\API\REST\Response\UnlimitedApiRate;

class CodebirdResponseParser
{
    /**
     * @param object $result
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function parseObject($result)
    {
        return $this->getResponse($result, false);
    }

    /**
     * @param object $result
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    public function parseList($result)
    {
        return $this->getResponse($result, true);
    }

    /**
     * Handles a twitter API response object
     *
     * @param object $result
     * @param bool   $isList
     *
     * @return ApiResponse
     *
     * @throws TwitterException
     */
    private function getResponse($result, $isList)
    {
        $this->handleErrors($result);

        $httpStatus = $this->getHttpStatus($result);
        $rate = $this->getRate($result);
        $content = $this->getContent($result, $isList);

        return new ApiResponse($httpStatus, $content, $rate);
    }

    /**
     * @param object $result
     *
     * @throws TwitterException
     */
    private function handleErrors($result)
    {
        if (isset($result->errors)) {
            $error = reset($result->errors);
            throw new TwitterException($error->message, $error->code);
        }
    }

    /**
     * @param object $result
     *
     * @return HttpStatus
     *
     * @throws TwitterException
     */
    private function getHttpStatus($result)
    {
        $httpStatus = new HttpStatus($result->httpstatus);
        if ($httpStatus->isError()) {
            throw new TwitterException($result->message);
        }

        return $httpStatus;
    }

    /**
     * @param object $result
     *
     * @return ApiRate
     */
    private function getRate($result)
    {
        if (isset($result->rate)) {
            return new LimitedApiRate(
                $result->rate['limit'],
                $result->rate['remaining'],
                $result->rate['reset']
            );
        }

        return new UnlimitedApiRate();
    }

    /**
     * @param object $result
     * @param bool   $isList
     *
     * @return object|array|null
     */
    private function getContent($result, $isList)
    {
        $content = $result;

        unset($content->httpstatus, $content->rate);

        if ($isList) {
            $content = [];
            foreach ($result as $index => $obj) {
                if (is_numeric($index)) {
                    $content[(int) $index] = $obj;
                }
            }
        } elseif (empty($content)) {
            $content = null;
        }

        return $content;
    }
}
