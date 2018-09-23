<?php
declare(strict_types = 1);

namespace Domain\Entity\ResourceAuthor;

interface Identity
{
    public function __toString(): string;
}
