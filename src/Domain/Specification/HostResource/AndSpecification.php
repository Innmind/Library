<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\HostResource as Entity,
};

final class AndSpecification extends ParentSpec implements Specification
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
        return $this->left()->isSatisfiedBy($relation) &&
            $this->right()->isSatisfiedBy($relation);
    }
}
