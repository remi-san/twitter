<?php

namespace Twitter\Serializer;

use Twitter\Object\TwitterSymbol;
use Twitter\TwitterSerializable;
use Twitter\TwitterSerializer;

class TwitterSymbolSerializer implements TwitterSerializer
{
    /**
     * @var TwitterEntityIndicesSerializer
     */
    private $entityIndicesSerializer;

    /**
     * Constructor
     *
     * @param TwitterEntityIndicesSerializer $entityIndicesSerializer
     */
    public function __construct(TwitterEntityIndicesSerializer $entityIndicesSerializer)
    {
        $this->entityIndicesSerializer  = $entityIndicesSerializer;
    }

    /**
     * @param  TwitterSerializable $object
     * @return \stdClass
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!$this->canSerialize($object)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterSymbol');
        }

        /* @var TwitterSymbol $object */
        $symbol = new \stdClass();
        $symbol->text = $object->getText();
        $symbol->indices = $this->entityIndicesSerializer->serialize($object->getIndices());

        return $symbol;
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return TwitterSymbol
     */
    public function unserialize($obj, array $context = [])
    {
        if (!$this->canUnserialize($obj)) {
            throw new \InvalidArgumentException('$object is not unserializable');
        }

        return TwitterSymbol::create(
            $obj->text,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }

    /**
     * @param  TwitterSerializable $object
     * @return boolean
     */
    public function canSerialize(TwitterSerializable $object)
    {
        return $object instanceof TwitterSymbol;
    }

    /**
     * @param  \stdClass $object
     * @return boolean
     */
    public function canUnserialize($object)
    {
        return isset($object->text) && isset($object->indices);
    }

    /**
     * @return TwitterSymbolSerializer
     */
    public static function build()
    {
        return new self(
            TwitterEntityIndicesSerializer::build()
        );
    }
}
