<?php
declare(strict_types = 1);

namespace Domain\Entity\Host;

interface Identity
{
    public function toString(): string;
}
