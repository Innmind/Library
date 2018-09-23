<?php
declare(strict_types = 1);

namespace Domain\Entity\HtmlPage;

use Domain\Exception\DomainException;

final class Anchor
{
    private $value;

    public function __construct(string $value)
    {
        $value = ltrim($value, '#');

        if (empty($value)) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $anchor): bool
    {
        return $this->value === $anchor->value;
    }

    public function __toString(): string
    {
        return '#'.$this->value;
    }
}
