<?php
declare(strict_types = 1);

namespace Domain\Specification\Citation;

use Domain\Entity\Citation as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Entity $citation): bool;
}
