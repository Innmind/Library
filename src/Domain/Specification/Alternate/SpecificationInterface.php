<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\Entity\Alternate as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface SpecificationInterface extends ParentSpec
{
    public function isSatisfiedBy(Entity $alternate): bool;
}
