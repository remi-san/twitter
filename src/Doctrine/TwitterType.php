<?php

namespace Twitter\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Twitter\Serializer\TwitterJsonSerializer;
use Twitter\TwitterSerializable;

class TwitterType extends Type
{
    const TWITTER = 'twitter';
    const SQL_TYPE = 'TEXT';

    /**
     * @var TwitterJsonSerializer
     */
    private $twitterSerializer;

    /**
     * Sets the serializer
     *
     * @param TwitterJsonSerializer $twitterSerializer
     */
    public function setSerializer(TwitterJsonSerializer $twitterSerializer)
    {
        $this->twitterSerializer = $twitterSerializer;
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return self::SQL_TYPE;
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::TWITTER;
    }

    /**
     * @param  string           $value
     * @param  AbstractPlatform $platform
     * @return TwitterSerializable
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $this->getSerializer()->unserialize($value);
    }

    /**
     * @param  mixed            $value
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof TwitterSerializable) {
            throw new \InvalidArgumentException('Value is not serializable!');
        }

        return $this->getSerializer()->serialize($value);
    }

    /**
     * @return TwitterJsonSerializer
     */
    private function getSerializer()
    {
        return $this->twitterSerializer;
    }
}
