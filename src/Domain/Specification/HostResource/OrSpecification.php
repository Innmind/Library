<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\HostResource as Entity,
};

final class OrSpecification extends ParentSpec implements Specification
{
    use Composable;

    public function __construct(
        Specification $left,
        Specification $right
    ) {
        parent::__construct($left, $right);
    }

    public function isSatisfiedBy(Entity $relation): bool
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        return $this->left()->isSatisfiedBy($relation) ||
            $this->right()->isSatisfiedBy($relation);
    }
}
