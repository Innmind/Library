<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\Image;

use Domain\Entity\Image\Description;
use Innmind\Neo4j\ONM\Type;

final class DescriptionType implements Type
{
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
}
