<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\Domain as Entity,
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

    public function isSatisfiedBy(Entity $domain): bool
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        return $this->left()->isSatisfiedBy($domain) &&
            $this->right()->isSatisfiedBy($domain);
    }
}
