<?php
namespace Twitter\Object;

use Twitter\TwitterMessage;

class TwitterDirectMessage extends AbstractMessage implements TwitterMessage
{
    /**
     * @var TwitterUser
     */
    private $recipient;

    /**
     * Constructor
     *
     * @param int             $id
     * @param TwitterUser     $recipient
     * @param TwitterUser     $sender
     * @param string          $text
     * @param \DateTime       $createdAt
     * @param TwitterEntities $entities
     */
    public function __construct(
        $id,
        TwitterUser $sender,
        TwitterUser $recipient,
        $text,
        \DateTime $createdAt,
        TwitterEntities $entities = null
    ) {
        parent::__construct($id, $sender, $text, $entities, $createdAt);

        $this->recipient = $recipient;
    }

    /**
     * @return TwitterUser
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'DM ['.$this->id.']';
    }
}
