<?php
declare(strict_types = 1);

namespace Domain\Specification\Host;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\Host as Entity,
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

    public function isSatisfiedBy(Entity $host): bool
    {
        return $this->left()->isSatisfiedBy($host) &&
            $this->right()->isSatisfiedBy($host);
    }
}
