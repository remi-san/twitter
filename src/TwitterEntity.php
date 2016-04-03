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
     * Init
     *
     * @param TwitterEntityIndices $indices
     */
    protected function initTwitterEntity(TwitterEntityIndices $indices = null)
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
