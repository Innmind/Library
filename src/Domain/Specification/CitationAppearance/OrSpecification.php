<?php
declare(strict_types = 1);

namespace Domain\Specification\CitationAppearance;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\CitationAppearance as Entity
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

    public function isSatisfiedBy(Entity $appearance): bool
    {
        return $this->left()->isSatisfiedBy($appearance) ||
            $this->right()->isSatisfiedBy($appearance);
    }
}
