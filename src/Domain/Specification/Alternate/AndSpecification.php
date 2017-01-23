<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\Alternate as Entity
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

    public function isSatisfiedBy(Entity $alternate): bool
    {
        return $this->left()->isSatisfiedBy($alternate) &&
            $this->right()->isSatisfiedBy($alternate);
    }
}
