<?php
namespace Twitter\Object;

use Twitter\TwitterEntity;

class TwitterHashtag extends TwitterEntity
{
    /**
     * @var string
     */
    private $text;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function __toString()
    {
        return '#'.$this->getText();
    }

    /**
     * Static constructor.
     *
     * @param string               $text
     * @param TwitterEntityIndices $indices
     *
     * @return TwitterHashtag
     */
    public static function create($text, TwitterEntityIndices $indices)
    {
        $obj = new self();

        $obj->initTwitterEntity($indices);

        $obj->text = $text;

        return $obj;
    }
}
