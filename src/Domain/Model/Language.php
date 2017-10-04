<?php
declare(strict_types = 1);

namespace Domain\Model;

use Domain\Exception\DomainException;
use Innmind\Immutable\Str;

final class Language
{
    private $value;

    public function __construct(string $value)
    {
        if (!(new Str($value))->matches('~^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*$~')) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
