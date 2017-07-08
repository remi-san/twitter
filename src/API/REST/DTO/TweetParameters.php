<?php

namespace Twitter\API\REST\DTO;

use Twitter\API\REST\ApiParameters;

class TweetParameters implements ApiParameters
{
    /** @var string */
    private $text;

    /** @var int */
    private $replyToId;

    /** @var bool */
    private $sensitive;

    /** @var Coordinates */
    private $coordinates;

    /** @var string */
    private $placeId;

    /** @var bool */
    private $displayCoordinates;

    /** @var bool */
    private $trimUser;

    /** @var int[] */
    private $mediaIds;

    /** @var bool */
    private $enableDirectMessageCommands;

    /** @var bool */
    private $failDirectMessageCommands;

    /**
     * TweetParameters constructor.
     *
     * @param string      $text
     * @param int         $replyToId
     * @param bool        $sensitive
     * @param Coordinates $coordinates
     * @param string      $placeId
     * @param bool        $displayCoordinates
     * @param bool        $trimUser
     * @param int[]       $mediaIds
     * @param bool        $enableDirectMessageCommands
     * @param bool        $failDirectMessageCommands
     */
    public function __construct(
        $text,
        $replyToId = null,
        $sensitive = false,
        Coordinates $coordinates = null,
        $placeId = null,
        $displayCoordinates = false,
        $trimUser = false,
        array $mediaIds = [],
        $enableDirectMessageCommands = true,
        $failDirectMessageCommands = false
    ) {
        $this->text = $text;
        $this->replyToId = $replyToId;
        $this->sensitive = $sensitive;
        $this->coordinates = $coordinates;
        $this->placeId = $placeId;
        $this->displayCoordinates = $displayCoordinates;
        $this->trimUser = $trimUser;
        $this->mediaIds = $mediaIds;
        $this->enableDirectMessageCommands = $enableDirectMessageCommands;
        $this->failDirectMessageCommands = $failDirectMessageCommands;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $parameters = [
            'status' => $this->text,
            'possibly_sensitive' => $this->sensitive ? 'true' : 'false',
            'trim_user' => $this->trimUser ? 'true' : 'false',
            'enable_dm_commands' => $this->enableDirectMessageCommands ? 'true' : 'false',
            'fail_dm_commands' => $this->failDirectMessageCommands ? 'true' : 'false'
        ];

        if ($this->replyToId !== null) {
            $parameters['in_reply_to_status_id'] = $this->replyToId;
        }

        if ($this->coordinates !== null) {
            $parameters = array_merge($parameters, $this->coordinates->toArray());

            $parameters['display_coordinates'] = $this->displayCoordinates ? 'true' : 'false';
        }

        if ($this->placeId !== null) {
            $parameters['place_id'] = $this->placeId;
        }

        if (!empty($this->mediaIds)) {
            $parameters['media_ids'] = implode(',', $this->mediaIds);
        }

        return $parameters;
    }
}
