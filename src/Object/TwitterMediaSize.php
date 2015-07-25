<?php
namespace Twitter\Object;

use Twitter\TwitterSerializable;

class TwitterMediaSize implements TwitterSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var string
     */
    private $resize;

    /**
     * Constructor
     *
     * @param string  $name
     * @param int     $width
     * @param int     $height
     * @param boolean $resize
     */
    public function __construct($name, $width, $height, $resize)
    {
        $this->height = $height;
        $this->name = $name;
        $this->resize = $resize;
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getResize()
    {
        return $this->resize;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}
