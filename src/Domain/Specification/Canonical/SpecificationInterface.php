<?php
declare(strict_types = 1);

namespace Domain\Specification\Canonical;

use Domain\Entity\Canonical as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface SpecificationInterface extends ParentSpec
{
    public function isSatisfiedBy(Entity $canonical): bool;
}
