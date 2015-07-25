<?php
namespace Twitter;

interface TwitterDatedObject extends TwitterObject
{
    /**
     * @return \DateTime
     */
    public function getDate();
}
