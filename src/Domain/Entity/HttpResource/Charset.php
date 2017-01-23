<?php
declare(strict_types = 1);

namespace Domain\Entity\HttpResource;

use Domain\Exception\InvalidArgumentException;
use Innmind\Immutable\StringPrimitive as Str;

final class Charset
{
    private $value;

    public function __construct(string $value)
    {
        if (!(new Str($value))->match('~^[a-zA-Z0-9\-_:\(\)]+$~')) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
