<?php
declare(strict_types = 1);

namespace Domain\Specification\Canonical;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\Canonical as Entity
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

    public function isSatisfiedBy(Entity $canonical): bool
    {
        return $this->left()->isSatisfiedBy($canonical) &&
            $this->right()->isSatisfiedBy($canonical);
    }
}
