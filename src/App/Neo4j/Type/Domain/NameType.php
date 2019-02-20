<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\Domain;

use Domain\Entity\Domain\Name;
use Innmind\Neo4j\ONM\Type;

final class NameType implements Type
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
        return new Name((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }
}
