<?php
declare(strict_types = 1);

namespace Domain\Model;

use Domain\Exception\InvalidArgumentException;
use Innmind\Immutable\StringPrimitive as Str;

final class Language
{
    private $value;

    public function __construct(string $value)
    {
        if (!(new Str($value))->match('~^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*$~')) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
