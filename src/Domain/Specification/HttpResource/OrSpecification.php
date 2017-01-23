<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\HttpResource as Entity
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

    public function isSatisfiedBy(Entity $resource): bool
    {
        return $this->left()->isSatisfiedBy($resource) ||
            $this->right()->isSatisfiedBy($resource);
    }
}
