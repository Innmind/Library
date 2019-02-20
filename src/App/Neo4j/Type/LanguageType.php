<?php
declare(strict_types = 1);

namespace App\Neo4j\Type;

use App\Exception\InvalidArgumentException;
use Domain\Model\Language;
use Innmind\Neo4j\ONM\Type;

final class LanguageType implements Type
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
        return new Language((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
