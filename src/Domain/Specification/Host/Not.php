<?php
declare(strict_types = 1);

namespace Domain\Specification\Host;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Host as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $host): bool
    {
        return !$this->specification()->isSatisfiedBy($host);
    }
}
