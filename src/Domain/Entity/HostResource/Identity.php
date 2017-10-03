<?php
declare(strict_types = 1);

namespace Domain\Entity\HostResource;

interface Identity
{
    public function __toString(): string;
}
