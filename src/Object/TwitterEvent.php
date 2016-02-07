<?php
namespace Twitter\Object;

use Twitter\TwitterDatedObject;
use Twitter\TwitterEventTarget;

class TwitterEvent implements TwitterDatedObject
{
    const ACCESS_REVOKED = 'access_revoked';
    const BLOCK = 'block';
    const UNBLOCK = 'unblock';
    const FAVORITE = 'favorite';
    const UNFAVORITE = 'unfavorite';
    const FOLLOW = 'follow';
    const UNFOLLOW = 'unfollow';
    const LIST_CREATED = 'list_created';
    const LIST_DESTROYED = 'list_destroyed';
    const LIST_UPDATED = 'list_updated';
    const LIST_MEMBER_ADDED = 'list_member_added';
    const LIST_MEMBER_REMOVED = 'list_member_removed';
    const LIST_USER_SUBSCRIBED = 'list_user_subscribed';
    const LIST_USER_UNSUBSCRIBED = 'list_user_unsubscribed';
    const USER_UPDATED = 'user_update';

    /**
     * @var string
     */
    private $type;

    /**
     * @var TwitterUser
     */
    private $source;

    /**
     * @var TwitterUser
     */
    private $target;

    /**
     * @var TwitterEventTarget
     */
    private $object;

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
     * @return TwitterEventTarget
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return TwitterUser
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return TwitterUser
     */
    public function getTarget()
    {
        return $this->target;
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
        return 'Event [' . $this->type . ']: ' . $this->object;
    }

    /**
     * Static constructor.
     *
     * @param string $type
     * @param TwitterUser $source
     * @param TwitterUser $target
     * @param TwitterEventTarget $object
     * @param \DateTimeInterface $date
     *
     * @return TwitterEvent
     */
    public static function create(
        $type,
        TwitterUser $source,
        TwitterUser $target = null,
        TwitterEventTarget $object = null,
        \DateTimeInterface $date = null
    ) {
        $obj = new self();

        $obj->type = $type;

        $obj->source = $source;
        $obj->target = $target;
        $obj->object = $object;

        $obj->date = $date;

        return $obj;
    }
}
