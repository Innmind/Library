<?php
declare(strict_types = 1);

namespace Domain\Specification\HttpResource;

use Innmind\Specification\{
    Specification as MasterSpec,
    Composite,
    Not as NotInterface,
};

trait Composable
{
    public function and(MasterSpec $specification): Composite
    {
        return new AndSpecification($this, $specification);
    }

    public function or(MasterSpec $specification): Composite
    {
        return new OrSpecification($this, $specification);
    }

    public function not(): NotInterface
    {
        return new Not($this);
    }
}
