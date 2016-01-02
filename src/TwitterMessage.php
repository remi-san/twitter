<?php
namespace Twitter;

use Twitter\Object\TwitterEntities;
use Twitter\Object\TwitterUser;

interface TwitterMessage extends TwitterDatedObject
{
    /**
     * @return TwitterMessageId
     */
    public function getId();

    /**
     * @return string
     */
    public function getText();

    /**
     * @return TwitterEntities
     */
    public function getEntities();

    /**
     * @return TwitterUser
     */
    public function getSender();

    /**
     * @return string[]
     */
    public function getFormattedHashtags();

    /**
     * @return string[]
     */
    public function getFormattedUserMentions();

    /**
     * @return string
     */
    public function getStrippedText();

    /**
     * @param  string $hashtag
     * @return bool
     */
    public function containsHashtag($hashtag);
}
