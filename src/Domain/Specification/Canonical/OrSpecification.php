<?php
declare(strict_types = 1);

namespace Domain\Specification\Canonical;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\Canonical as Entity,
};

final class OrSpecification extends ParentSpec implements Specification
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
        /** @psalm-suppress UndefinedInterfaceMethod */
        return $this->left()->isSatisfiedBy($canonical) ||
            $this->right()->isSatisfiedBy($canonical);
    }
}
