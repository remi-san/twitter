<?php
namespace Twitter\Object;

use Twitter\TwitterMessage;

abstract class AbstractMessage implements TwitterMessage {

    /**
     * @var int
     */
    protected $id;

    /**
     * @var TwitterUser
     */
    protected $sender;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var TwitterEntities
     */
    protected $entities;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * Constructor
     *
     * @param int             $id
     * @param TwitterUser     $sender
     * @param string          $text
     * @param TwitterEntities $entities
     * @param \DateTime       $date
     */
    function __construct($id, TwitterUser $sender, $text, TwitterEntities $entities = null, \DateTime $date = null)
    {
        $this->entities = $entities;
        $this->id = $id;
        $this->sender = $sender;
        $this->text = $text;
        $this->date = $date;
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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return TwitterEntities
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @return TwitterUser
     */
    public function getSender()
    {
        return $this->sender;
    }


    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @return string[]
     */
    public function getFormattedHashtags()
    {
        $formattedHashtags = array();
        if ($this->entities && $this->entities->getHashtags()) {
            foreach ($this->entities->getHashtags() as $hashtag) {
                $formattedHashtags[] = $hashtag->__toString();
            }
        }
        return $formattedHashtags;
    }

    /**
     * @return string[]
     */
    public function getFormattedUserMentions()
    {
        $formattedUserMentions = array();
        if ($this->entities && $this->entities->getUserMentions()) {
            foreach ($this->entities->getUserMentions() as $userMention) {
                $formattedUserMentions[] = $userMention->__toString();
            }
        }
        return $formattedUserMentions;
    }

    /**
     * @return string
     */
    public function getStrippedText()
    {
        $formattedHashtags = $this->getFormattedHashtags();
        $formattedUserMentions = $this->getFormattedUserMentions();
        return trim(str_ireplace(array_merge($formattedHashtags, $formattedUserMentions), '', $this->text));
    }

    /**
     * @param  string $text
     * @return bool
     */
    public function containsHashtag($text)
    {
        if ($this->entities && $this->entities->getHashtags()) {
            foreach ($this->entities->getHashtags() as $hashtag) {
                if ($hashtag->getText() == $text) {
                    return true;
                }
            }
        }
        return false;
    }
}