<?php
declare(strict_types = 1);

namespace Domain\Entity\HostResource;

interface IdentityInterface
{
    public function __toString(): string;
}
