<?php
declare(strict_types = 1);

namespace Domain\Specification\Canonical;

use Domain\Entity\Canonical as Entity;
use Innmind\Specification\Specification as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Entity $canonical): bool;
}
