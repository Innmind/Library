<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\Entity\Alternate as Entity;
use Innmind\Specification\Specification as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Entity $alternate): bool;
}
