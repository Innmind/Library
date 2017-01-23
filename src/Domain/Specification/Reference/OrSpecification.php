<?php
declare(strict_types = 1);

namespace Domain\Specification\Reference;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\Reference as Entity
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

    public function isSatisfiedBy(Entity $reference): bool
    {
        return $this->left()->isSatisfiedBy($reference) ||
            $this->right()->isSatisfiedBy($reference);
    }
}
