<?php
namespace Twitter;

class TwitterMessageId
{
    /**
     * @var string
     */
    private $id;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * Static constructor.
     *
     * @param  mixed $id
     *
     * @return TwitterMessageId
     */
    public static function create($id)
    {
        $obj = new self();

        $obj->id = $id;

        return $obj;
    }
}
