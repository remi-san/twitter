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
     * Constructor.
     */
    public function __construct()
    {
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

    /**
     * Static constructor.
     *
     * @param TwitterMessageId   $id
     * @param TwitterUser        $recipient
     * @param TwitterUser        $sender
     * @param string             $text
     * @param \DateTimeInterface $createdAt
     * @param TwitterEntities    $entities
     *
     * @return TwitterDirectMessage
     */
    public static function create(
        TwitterMessageId $id,
        TwitterUser $sender,
        TwitterUser $recipient,
        $text,
        \DateTimeInterface $createdAt,
        TwitterEntities $entities = null
    ) {
        $obj = new self();

        $obj->init($id, $sender, $text, $entities, $createdAt);

        $obj->recipient = $recipient;

        return $obj;
    }
}
