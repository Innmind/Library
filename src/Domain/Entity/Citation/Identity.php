<?php
declare(strict_types = 1);

namespace Domain\Entity\Citation;

interface Identity
{
    public function __toString(): string;
}
