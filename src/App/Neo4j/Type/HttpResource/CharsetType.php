<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\HttpResource;

use Domain\Entity\HttpResource\Charset;
use Innmind\Neo4j\ONM\Type;

final class CharsetType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        if ($value === null) {
            return;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Charset((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return true;
    }
}
