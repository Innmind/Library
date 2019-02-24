<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\HttpResource;

use Innmind\Url\{
    Query,
    NullQuery,
};
use Innmind\Neo4j\ONM\Type;

final class QueryType implements Type
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
        $value = (string) $value;

        return empty($value) ? new NullQuery : new Query($value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }
}
