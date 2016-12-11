<?php
declare(strict_types = 1);

namespace Domain\Specification\ResourceAuthor;

use Domain\Entity\ResourceAuthor as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface SpecificationInterface extends ParentSpec
{
    public function isSatisfiedBy(Entity $relation): bool;
}
