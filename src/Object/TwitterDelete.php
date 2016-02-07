<?php
namespace Twitter\Object;

use Twitter\TwitterDatedObject;

class TwitterDelete implements TwitterDatedObject
{
    const TWEET = 'tweet';
    const DM = 'direct message';

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var \DateTimeInterface
     */
    private $date;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Delete ['.$this->type.']['.$this->id.']';
    }

    /**
     * Static constructor.
     *
     * @param string             $type
     * @param int                $id
     * @param int                $userId
     * @param \DateTimeInterface $date
     *
     * @return TwitterDelete
     */
    public static function create($type, $id, $userId, \DateTimeInterface $date)
    {
        $obj = new self();

        $obj->type = $type;
        $obj->id = $id;
        $obj->userId = $userId;
        $obj->date = $date;

        return $obj;
    }
}
