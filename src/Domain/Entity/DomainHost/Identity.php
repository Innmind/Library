<?php
declare(strict_types = 1);

namespace Domain\Entity\DomainHost;

interface Identity
{
    public function __toString(): string;
}
