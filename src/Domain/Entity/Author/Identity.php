<?php
declare(strict_types = 1);

namespace Domain\Entity\Author;

interface Identity
{
    public function toString(): string;
}
