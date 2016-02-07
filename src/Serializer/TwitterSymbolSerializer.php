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
     * @return array
     */
    public function serialize(TwitterSerializable $object)
    {
        if (!($object instanceof TwitterSymbol)) {
            throw new \InvalidArgumentException('$object must be an instance of TwitterSymbol');
        }

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
    public function unserialize($obj, array $context = array())
    {
        return TwitterSymbol::create(
            $obj->text,
            $this->entityIndicesSerializer->unserialize($obj->indices)
        );
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
