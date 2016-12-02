<?php
declare(strict_types = 1);

namespace Domain\Entity\Reference;

interface IdentityInterface
{
    public function __toString(): string;
}
