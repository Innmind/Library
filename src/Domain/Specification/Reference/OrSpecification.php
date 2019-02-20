<?php
declare(strict_types = 1);

namespace Domain\Specification\Reference;

use Domain\{
    Specification\OrSpecification as ParentSpec,
    Entity\Reference as Entity,
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

    public function isSatisfiedBy(Entity $reference): bool
    {
        return $this->left()->isSatisfiedBy($reference) ||
            $this->right()->isSatisfiedBy($reference);
    }
}
