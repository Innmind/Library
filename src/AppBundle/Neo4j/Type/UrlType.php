<?php
declare(strict_types = 1);

namespace AppBundle\Neo4j\Type;

use Innmind\Url\Url;
use Innmind\Neo4j\ONM\{
    TypeInterface,
    Types
};
use Innmind\Immutable\{
    MapInterface,
    SetInterface,
    Set
};

final class UrlType implements TypeInterface
{
    private static $identifiers;
    private $nullable = false;

    /**
     * {@inheritdoc}
     */
    public static function fromConfig(MapInterface $config, Types $types): TypeInterface
    {
        $self = new self;

        if ($config->contains('nullable')) {
            $self->nullable = true;
        }

        return $self;
    }

    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        if ($this->isNullable() && $value === null) {
            return;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return Url::fromString((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * {@inheritdoc}
     */
    public static function identifiers(): SetInterface
    {
        if (self::$identifiers === null) {
            self::$identifiers = (new Set('string'))->add('url');
        }

        return self::$identifiers;
    }
}
