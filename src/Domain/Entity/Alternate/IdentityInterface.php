<?php
declare(strict_types = 1);

namespace Domain\Entity\Alternate;

interface IdentityInterface
{
    public function __toString(): string;
}
