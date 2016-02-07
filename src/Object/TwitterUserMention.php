<?php
namespace Twitter\Object;

use Twitter\TwitterBasicUser;
use Twitter\TwitterEntity;

class TwitterUserMention extends TwitterEntity implements TwitterBasicUser
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
     * Construct.
     */
    public function __construct()
    {
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

    /**
     * Static constructor.
     *
     * @param int                  $id
     * @param string               $screenName
     * @param string               $name
     * @param TwitterEntityIndices $indices
     *
     * @return TwitterUserMention
     */
    public static function create($id, $screenName, $name, TwitterEntityIndices $indices)
    {
        $obj = new self();

        $obj->initTwitterEntity($indices);

        $obj->id = $id;
        $obj->name = $name;
        $obj->screenName = $screenName;

        return $obj;
    }
}
