<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\Citation;

use Domain\Entity\Citation\Text;
use Innmind\Neo4j\ONM\Type;

final class TextType implements Type
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
        return new Text((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }
}
