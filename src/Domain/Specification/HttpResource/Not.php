<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\HttpResource as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $resource): bool
    {
        return !$this->specification()->isSatisfiedBy($resource);
    }
}
