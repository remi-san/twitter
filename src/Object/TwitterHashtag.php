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
     * Constructor
     *
     * @param string               $text
     * @param TwitterEntityIndices $indices
     */
    public function __construct($text, TwitterEntityIndices $indices)
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

    public function __toString()
    {
        return '#'.$this->getText();
    }
}
