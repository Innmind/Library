<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\Entity\Domain as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface SpecificationInterface extends ParentSpec
{
    public function isSatisfiedBy(Entity $domain): bool;
}
