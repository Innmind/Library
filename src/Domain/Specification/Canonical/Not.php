<?php
declare(strict_types = 1);

namespace Domain\Specification\Canonical;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Canonical as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $canonical): bool
    {
        return !$this->specification()->isSatisfiedBy($canonical);
    }
}
