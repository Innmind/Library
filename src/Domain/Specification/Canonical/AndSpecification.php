<?php
declare(strict_types = 1);

namespace Domain\Specification\Canonical;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\Canonical as Entity,
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

    public function isSatisfiedBy(Entity $canonical): bool
    {
        return $this->left()->isSatisfiedBy($canonical) &&
            $this->right()->isSatisfiedBy($canonical);
    }
}
