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
     * Constructor.
     */
    public function __construct()
    {
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

    /**
     * Static constructor.
     *
     * @param string  $name
     * @param int     $width
     * @param int     $height
     * @param boolean $resize
     *
     * @return TwitterMediaSize
     */
    public static function create($name, $width, $height, $resize)
    {
        $obj = new self();

        $obj->height = $height;
        $obj->name = $name;
        $obj->resize = $resize;
        $obj->width = $width;

        return $obj;
    }
}
