<?php
declare(strict_types = 1);

namespace Domain\Specification\DomainHost;

use Domain\Entity\DomainHost as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface SpecificationInterface extends ParentSpec
{
    public function isSatisfiedBy(Entity $relation): bool;
}
