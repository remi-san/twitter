<?php

namespace Twitter\Object;

use Twitter\TwitterSerializable;

class TwitterCoordinates implements TwitterSerializable
{
    const TYPE_POINT = 'point';

    /**
     * @var string
     */
    private $type;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Static constructor.
     *
     * @param float  $latitude
     * @param float  $longitude
     * @param string $type
     *
     * @return TwitterCoordinates
     */
    public static function create($longitude, $latitude, $type)
    {
        $obj = new self();

        $obj->latitude = $latitude;
        $obj->longitude = $longitude;
        $obj->type = $type;

        return $obj;
    }
}
