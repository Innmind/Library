<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\Entity\Alternate;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface SpecificationInterface extends ParentSpec
{
    public function isSatisfiedBy(Alternate $alternate): bool;
}
