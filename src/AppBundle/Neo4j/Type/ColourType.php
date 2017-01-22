<?php
declare(strict_types = 1);

namespace AppBundle\Neo4j\Type;

use Innmind\Colour\RGBA;
use Innmind\Neo4j\ONM\TypeInterface;
use Innmind\Immutable\{
    CollectionInterface,
    SetInterface,
    Set
};

final class ColourType implements TypeInterface
{
    private static $identifiers;
    private $nullable = false;

    /**
     * {@inheritdoc}
     */
    public static function fromConfig(CollectionInterface $config): TypeInterface
    {
        $self = new self;

        if ($config->hasKey('nullable')) {
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

        return (string) $value->toRGBA();
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return RGBA::fromString((string) $value);
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
            self::$identifiers = (new Set('string'))->add('colour');
        }

        return self::$identifiers;
    }
}
