<?php
declare(strict_types = 1);

namespace Domain\Specification\Reference;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Reference as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $reference): bool
    {
        return !$this->specification()->isSatisfiedBy($reference);
    }
}
