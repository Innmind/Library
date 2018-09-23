<?php
declare(strict_types = 1);

namespace App\Neo4j\Type;

use App\Exception\InvalidArgumentException;
use Domain\Model\Language;
use Innmind\Neo4j\ONM\{
    Type,
    Types
};
use Innmind\Immutable\{
    MapInterface,
    SetInterface,
    Set
};

final class LanguageType implements Type
{
    private static $identifiers;
    private $nullable = false;

    /**
     * {@inheritdoc}
     */
    public static function fromConfig(MapInterface $config, Types $types): Type
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

        if ($value === null) {
            throw new InvalidArgumentException;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Language((string) $value);
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
            self::$identifiers = (new Set('string'))->add('language');
        }

        return self::$identifiers;
    }
}
