<?php
declare(strict_types = 1);

namespace Domain\Specification\Host;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\Host as Entity
};

final class AndSpecification extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(
        SpecificationInterface $left,
        SpecificationInterface $right
    ) {
        parent::__construct($left, $right);
    }

    public function isSatisfiedBy(Entity $host): bool
    {
        return $this->left()->isSatisfiedBy($host) &&
            $this->right()->isSatisfiedBy($host);
    }
}