<?php
declare(strict_types = 1);

namespace Domain\Entity\ResourceAuthor;

interface IdentityInterface
{
    public function __toString(): string;
}
