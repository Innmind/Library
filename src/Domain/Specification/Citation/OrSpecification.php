<?php
declare(strict_types = 1);

namespace Domain\Specification\Citation;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\Citation as Entity
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

    public function isSatisfiedBy(Entity $citation): bool
    {
        return $this->left()->isSatisfiedBy($citation) ||
            $this->right()->isSatisfiedBy($citation);
    }
}
