<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\{
    Specification,
    Composite,
    Not,
};

trait Composable
{
    public function and(Specification $specification): Composite
    {
        return new AndSpecification($this, $specification);
    }

    public function or(Specification $specification): Composite
    {
        return new OrSpecification($this, $specification);
    }

    public function not(): Not
    {
        return new Not($this);
    }
}
