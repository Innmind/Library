<?php
declare(strict_types = 1);

namespace AppBundle\Neo4j\Type\Image;

use Domain\Entity\Image\Description;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    CollectionInterface,
    SetInterface,
    Set
};

final class DescriptionType implements TypeInterface
{
    private static $identifiers;

    /**
     * {@inheritdoc}
     */
    public static function fromConfig(CollectionInterface $config): TypeInterface
    {
        return new self;
    }

    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Description((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function identifiers(): SetInterface
    {
        if (self::$identifiers === null) {
            self::$identifiers = (new Set('string'))->add('image_description');
        }

        return self::$identifiers;
    }
}