<?php
declare(strict_types = 1);

namespace Domain\Entity\Image;

use Domain\Exception\InvalidArgumentException;

final class Weight
{
    private $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
