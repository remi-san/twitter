<?php
namespace Twitter;

use Twitter\Object\TwitterEntityIndices;

abstract class TwitterEntity implements TwitterSerializable
{
    /**
     * @var TwitterEntityIndices
     */
    protected $indices;

    /**
     * Constructor
     *
     * @param TwitterEntityIndices $indices
     */
    public function __construct(TwitterEntityIndices $indices)
    {
        $this->indices = $indices;
    }

    /**
     * @return TwitterEntityIndices
     */
    public function getIndices()
    {
        return $this->indices;
    }
}
