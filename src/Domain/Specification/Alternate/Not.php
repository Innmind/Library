<?php
declare(strict_types = 1);

namespace Domain\Specification\Alternate;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Alternate as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $alternate): bool
    {
        return !$this->specification()->isSatisfiedBy($alternate);
    }
}
