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
     * Constructor
     *
     * @param string               $text
     * @param TwitterEntityIndices $indices
     */
    function __construct($text, TwitterEntityIndices $indices)
    {
        parent::__construct($indices);
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
} 