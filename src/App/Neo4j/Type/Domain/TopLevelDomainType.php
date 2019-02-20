<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\Domain;

use Domain\Entity\Domain\TopLevelDomain;
use Innmind\Neo4j\ONM\Type;

final class TopLevelDomainType implements Type
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
        return new TopLevelDomain((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }
}
