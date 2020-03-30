<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\Image;

use Domain\Entity\Image\Weight;
use Innmind\Neo4j\ONM\Type;

final class WeightType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        if ($value === null) {
            return;
        }

        /** @psalm-suppress MixedMethodCall */
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
}
