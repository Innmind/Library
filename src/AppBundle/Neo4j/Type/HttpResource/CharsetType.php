<?php
declare(strict_types = 1);

namespace AppBundle\Neo4j\Type\HttpResource;

use Domain\Entity\HttpResource\Charset;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    CollectionInterface,
    SetInterface,
    Set
};

final class CharsetType implements TypeInterface
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
        if ($value === null) {
            return;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Charset((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function identifiers(): SetInterface
    {
        if (self::$identifiers === null) {
            self::$identifiers = (new Set('string'))->add('http_resource_charset');
        }

        return self::$identifiers;
    }
}
