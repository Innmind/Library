<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\Entity\HostResource as Entity;
use Innmind\Specification\Specification as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Entity $relation): bool;
}
