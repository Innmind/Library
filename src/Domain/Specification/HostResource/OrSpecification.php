<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\HostResource as Entity
};

final class OrSpecification extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(
        SpecificationInterface $left,
        SpecificationInterface $right
    ) {
        parent::__construct($left, $right);
    }

    public function isSatisfiedBy(Entity $relation): bool
    {
        return $this->left()->isSatisfiedBy($relation) ||
            $this->right()->isSatisfiedBy($relation);
    }
}
