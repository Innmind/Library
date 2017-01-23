<?php
declare(strict_types = 1);

namespace Domain\Entity\Citation;

interface IdentityInterface
{
    public function __toString(): string;
}
