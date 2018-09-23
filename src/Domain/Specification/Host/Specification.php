<?php
declare(strict_types = 1);

namespace Domain\Specification\Host;

use Domain\Entity\Host as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Entity $host): bool;
}
