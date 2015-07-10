<?php
namespace Twitter\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Twitter\Serializer\TwitterJsonSerializer;
use Twitter\TwitterObject;

class TwitterType extends Type {

    const TWITTER = 'twitter';

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
        return 'TEXT';
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
     * @return TwitterObject
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $this->twitterSerializer->unserialize($value);
    }

    /**
     * @param  TwitterObject    $value
     * @param  AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $this->twitterSerializer->serialize($value);
    }
}