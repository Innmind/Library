<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\HttpResource;

use Innmind\Url\Query;
use Innmind\Neo4j\ONM\Type;

final class QueryType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        return $value->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        $value = (string) $value;

        return empty($value) ? Query::none() : Query::of($value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }
}
