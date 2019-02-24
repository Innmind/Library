<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\HttpResource as Entity,
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

    public function isSatisfiedBy(Entity $resource): bool
    {
        return $this->left()->isSatisfiedBy($resource) &&
            $this->right()->isSatisfiedBy($resource);
    }
}
