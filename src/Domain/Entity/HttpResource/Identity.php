<?php
declare(strict_types = 1);

namespace Domain\Entity\HttpResource;

interface Identity
{
    public function toString(): string;
}
