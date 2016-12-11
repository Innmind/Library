<?php
declare(strict_types = 1);

namespace Domain\Specification\Citation;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Citation as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $citation): bool
    {
        return !$this->specification()->isSatisfiedBy($citation);
    }
}
