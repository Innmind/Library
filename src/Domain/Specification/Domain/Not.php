<?php
declare(strict_types = 1);

namespace Domain\Specification\Domain;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Domain as Entity,
};

final class Not extends ParentSpec implements Specification
{
    use Composable;

    public function __construct(Specification $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Entity $domain): bool
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        return !$this->specification()->isSatisfiedBy($domain);
    }
}
