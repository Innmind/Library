<?php
declare(strict_types = 1);

namespace App\Neo4j\Type;

use Innmind\Colour\RGBA;
use Innmind\Neo4j\ONM\Type;

final class ColourType implements Type
{
    private bool $nullable = false;

    public static function nullable(): self
    {
        $self = new self;
        $self->nullable = true;

        return $self;
    }

    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        if ($this->isNullable() && $value === null) {
            return;
        }

        /** @psalm-suppress MixedMethodCall */
        return $value->toRGBA()->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return RGBA::of((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
