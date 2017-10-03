<?php
declare(strict_types = 1);

namespace Domain\Specification\CitationAppearance;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\CitationAppearance as Entity
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

    public function isSatisfiedBy(Entity $appearance): bool
    {
        return $this->left()->isSatisfiedBy($appearance) &&
            $this->right()->isSatisfiedBy($appearance);
    }
}
