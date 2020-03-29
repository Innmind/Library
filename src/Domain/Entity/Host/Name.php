<?php
declare(strict_types = 1);

namespace Domain\Entity\Host;

use Domain\Exception\DomainException;

final class Name
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
