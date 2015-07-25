<?php
namespace Twitter\Object;

use Twitter\TwitterSerializable;

class TwitterCoordinates implements TwitterSerializable
{
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
     * Constructor
     *
     * @param float  $latitude
     * @param float  $longitude
     * @param string $type
     */
    public function __construct($longitude, $latitude, $type)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->type = $type;
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
}
