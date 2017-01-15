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

final class DescriptionsType implements TypeInterface
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
        return $value->reduce(
            [],
            function(array $carry, Description $description): array {
                $carry[] = (string) $description;

                return $carry;
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        $set = new Set(Description::class);

        foreach ($value as $description) {
            $set = $set->add(new Description((string) $description));
        }

        return $set;
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
            self::$identifiers = (new Set('string'))->add('image_descriptions');
        }

        return self::$identifiers;
    }
}
