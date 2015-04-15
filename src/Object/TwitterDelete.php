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
     * @var \DateTime
     */
    private $date;

    /**
     * Constructor
     *
     * @param string    $type
     * @param int       $id
     * @param int       $userId
     * @param \DateTime $date
     */
    function __construct($type, $id, $userId, \DateTime $date)
    {
        $this->type = $type;
        $this->id = $id;
        $this->userId = $userId;
        $this->date = $date;
    }

    /**
     * @return \DateTime
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
    public function __toString() {
        return 'Delete ['.$this->type.']['.$this->id.']';
    }
}