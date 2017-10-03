<?php
declare(strict_types = 1);

namespace Domain\Entity\Canonical;

interface Identity
{
    public function __toString(): string;
}
