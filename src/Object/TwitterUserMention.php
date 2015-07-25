<?php
namespace Twitter\Object;

use Twitter\TwitterEntity;

class TwitterUserMention extends TwitterEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $screenName;

    /**
     * @var string
     */
    private $name;

    /**
     * @param int                  $id
     * @param string               $screenName
     * @param string               $name
     * @param TwitterEntityIndices $indices
     */
    public function __construct($id, $screenName, $name, TwitterEntityIndices $indices)
    {
        parent::__construct($indices);
        $this->id = $id;
        $this->name = $name;
        $this->screenName = $screenName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '@'.$this->screenName;
    }
}
