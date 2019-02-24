<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\Image;

use Domain\Entity\Image\Dimension;
use Innmind\Neo4j\ONM\Type;

final class DimensionType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        if ($value === null) {
            return;
        }

        return [$value->width(), $value->height()];
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Dimension((int) $value[0], (int) $value[1]);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return true;
    }
}
