<?php
namespace Twitter\Serializer;

use Twitter\Object\TwitterUser;
use Twitter\Serializer;

class TwitterJsonSerializer implements Serializer
{

    /**
     * @var TwitterEventTargetSerializer
     */
    private $twitterTargetSerializer;

    /**
     * @var TwitterDirectMessageSerializer
     */
    private $directMessageSerializer;

    /**
     * @var TwitterEventSerializer
     */
    private $twitterEventSerializer;

    /**
     * @var TwitterFriendsSerializer
     */
    private $twitterFriendsSerializer;

    /**
     * @var TwitterDisconnectSerializer
     */
    private $twitterDisconnectSerializer;

    /**
     * @var TwitterDeleteSerializer
     */
    private $twitterDeleteSerializer;

    /**
     * @var TwitterUserSerializer
     */
    private $twitterUserSerializer;

    /**
     * Constructor
     *
     * @param TwitterEventTargetSerializer $twitterTargetSerializer
     * @param TwitterDirectMessageSerializer $directMessageSerializer
     * @param TwitterEventSerializer $twitterEventSerializer
     * @param TwitterFriendsSerializer $twitterFriendsSerializer
     * @param TwitterDisconnectSerializer $twitterDisconnectSerializer
     * @param TwitterDeleteSerializer $twitterDeleteSerializer
     * @param TwitterUserSerializer $twitterUserSerializer
     */
    function __construct(
        TwitterEventTargetSerializer $twitterTargetSerializer,
        TwitterDirectMessageSerializer $directMessageSerializer,
        TwitterEventSerializer $twitterEventSerializer,
        TwitterFriendsSerializer $twitterFriendsSerializer,
        TwitterDisconnectSerializer $twitterDisconnectSerializer,
        TwitterDeleteSerializer $twitterDeleteSerializer,
        TwitterUserSerializer $twitterUserSerializer
    )
    {
        $this->twitterTargetSerializer = $twitterTargetSerializer;
        $this->directMessageSerializer = $directMessageSerializer;
        $this->twitterEventSerializer = $twitterEventSerializer;
        $this->twitterFriendsSerializer = $twitterFriendsSerializer;
        $this->twitterDisconnectSerializer = $twitterDisconnectSerializer;
        $this->twitterDeleteSerializer = $twitterDeleteSerializer;
        $this->twitterUserSerializer = $twitterUserSerializer;
    }

    /**
     * @param  object $object
     * @return string
     */
    public function serialize($object)
    {
        if ($object instanceof TwitterUser) {
            return json_encode($this->twitterUserSerializer->serialize($object));
        }
        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  string $string
     * @return object
     */
    public function unserialize($string)
    {
        $obj = json_decode($string);

        $object = null;

        if (isset($obj->text) && isset($obj->user)) {
            $object = $this->twitterTargetSerializer->unserialize($obj);
        } else if (isset($obj->direct_message)) {
            $object = $this->directMessageSerializer->unserialize($obj->direct_message);
        } else if (isset($obj->event)) {
            $object = $this->twitterEventSerializer->unserialize($obj);
        } else if (isset($obj->friends)) {
            $object = $this->twitterFriendsSerializer->unserialize($obj);
        } else if (isset($obj->disconnect)) {
            $object = $this->twitterDisconnectSerializer->unserialize($obj);
        } else if (isset($obj->delete)) {
            $object = $this->twitterDeleteSerializer->unserialize($obj);
        } else if (isset($obj->screen_name)) {
            $object = $this->twitterUserSerializer->unserialize($obj);
        }

        return $object;
    }
}