<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\HtmlPage;

use Domain\Entity\HtmlPage\Anchor;
use Innmind\Neo4j\ONM\Type;

final class AnchorType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        return $value->value();
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Anchor((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }
}
