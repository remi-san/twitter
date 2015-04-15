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
     * @param  \Twitter\TwitterSerializable $object
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterSymbol)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterSymbol');
        }

        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * @param  \stdClass $obj
     * @param  array     $context
     * @return \Twitter\Object\TwitterSymbol
     */
    public function unserialize($obj, array $context = array())
    {
        return new TwitterSymbol(
            $obj->text,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
    }
}