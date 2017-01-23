<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Domain as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $domain): bool
    {
        return !$this->specification()->isSatisfiedBy($domain);
    }
}
