<?php

namespace Twitter\Object;

use Twitter\TwitterEntity;

class TwitterSymbol extends TwitterEntity
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

    /**
     * Static constructor.
     *
     * @param string               $text
     * @param TwitterEntityIndices $indices
     *
     * @return TwitterSymbol
     */
    public static function create($text, TwitterEntityIndices $indices)
    {
        $obj = new self();

        $obj->initTwitterEntity($indices);

        $obj->text = $text;

        return $obj;
    }
}
