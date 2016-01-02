<?php
namespace Twitter;

interface TwitterDatedObject extends TwitterObject
{
    /**
     * @return \DateTimeInterface
     */
    public function getDate();
}
