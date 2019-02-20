<?php
declare(strict_types = 1);

namespace Domain\Specification\DomainHost;

use Domain\Entity\DomainHost as Entity;
use Innmind\Specification\Specification as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Entity $relation): bool;
}
