<?php
declare(strict_types = 1);

namespace AppBundle\Neo4j\Type\Image;

use Domain\Entity\Image\Weight;
use Innmind\Neo4j\ONM\{
    Type,
    Types
};
use Innmind\Immutable\{
    MapInterface,
    SetInterface,
    Set
};

final class WeightType implements Type
{
    private static $identifiers;

    /**
     * {@inheritdoc}
     */
    public static function fromConfig(MapInterface $config, Types $types): Type
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

        return $value->toInt();
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Weight((int) $value);
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
            self::$identifiers = (new Set('string'))->add('image_weight');
        }

        return self::$identifiers;
    }
}
