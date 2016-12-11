<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\Domain as Entity
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

    public function isSatisfiedBy(Entity $domain): bool
    {
        return $this->left()->isSatisfiedBy($domain) ||
            $this->right()->isSatisfiedBy($domain);
    }
}
