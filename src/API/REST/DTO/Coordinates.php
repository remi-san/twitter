<?php

namespace Twitter\API\REST\DTO;

use Twitter\API\REST\ApiParameters;

class Coordinates implements ApiParameters
{
    /** @var float */
    private $lat;

    /** @var float */
    private $long;

    /**
     * Coordinates constructor.
     *
     * @param float $lat
     * @param float $long
     */
    public function __construct($lat, $long)
    {
        $this->lat = $lat;
        $this->long = $long;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'lat' => $this->lat,
            'long' => $this->long,
        ];
    }
}
