<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\Entity\HttpResource as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Entity $resource): bool;
}
