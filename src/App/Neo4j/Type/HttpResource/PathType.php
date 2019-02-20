<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\HttpResource;

use Innmind\Url\Path;
use Innmind\Neo4j\ONM\Type;

final class PathType implements Type
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
        return new Path((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }
}
