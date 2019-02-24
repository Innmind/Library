<?php
declare(strict_types = 1);

namespace App\Neo4j\Type;

use App\Exception\InvalidArgumentException;
use Innmind\Url\Url;
use Innmind\Neo4j\ONM\Type;

final class UrlType implements Type
{
    private $nullable = false;

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

        if ($value === null) {
            throw new InvalidArgumentException;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return Url::fromString((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
