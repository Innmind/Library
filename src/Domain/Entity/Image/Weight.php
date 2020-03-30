<?php
declare(strict_types = 1);

namespace Domain\Entity\Image;

use Domain\Exception\DomainException;

final class Weight
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
