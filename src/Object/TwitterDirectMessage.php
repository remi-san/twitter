<?php
namespace Twitter\Object;

use Twitter\TwitterMessage;
use Twitter\TwitterMessageId;

class TwitterDirectMessage extends AbstractMessage implements TwitterMessage
{
    /**
     * @var TwitterUser
     */
    private $recipient;

    /**
     * Constructor
     *
     * @param TwitterMessageId   $id
     * @param TwitterUser        $recipient
     * @param TwitterUser        $sender
     * @param string             $text
     * @param \DateTimeInterface $createdAt
     * @param TwitterEntities    $entities
     */
    public function __construct(
        TwitterMessageId $id,
        TwitterUser $sender,
        TwitterUser $recipient,
        $text,
        \DateTimeInterface $createdAt,
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
