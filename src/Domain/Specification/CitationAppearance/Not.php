<?php
declare(strict_types = 1);

namespace Domain\Specification\CitationAppearance;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\CitationAppearance as Entity
};

final class Not extends ParentSpec implements SpecificationInterface
{
    use Composable;

    public function __construct(SpecificationInterface $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $appearance): bool
    {
        return !$this->specification()->isSatisfiedBy($appearance);
    }
}
