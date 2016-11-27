<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\{
    SpecificationInterface,
    CompositeInterface,
    NotInterface
};

trait Composable
{
    public function and(SpecificationInterface $specification): CompositeInterface
    {
        return new AndSpecification($this, $specification);
    }

    public function or(SpecificationInterface $specification): CompositeInterface
    {
        return new OrSpecification($this, $specification);
    }

    public function not(): NotInterface
    {
        return new Not($this);
    }
}
