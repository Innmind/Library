<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\HostResource as Entity,
};

final class Not extends ParentSpec implements Specification
{
    use Composable;

    public function __construct(Specification $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $relation): bool
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        return !$this->specification()->isSatisfiedBy($relation);
    }
}
