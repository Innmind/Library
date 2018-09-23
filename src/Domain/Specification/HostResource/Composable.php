<?php
declare(strict_types = 1);

namespace Domain\Specification\HostResource;

use Innmind\Specification\{
    SpecificationInterface as MasterSpec,
    CompositeInterface,
    NotInterface
};

trait Composable
{
    public function and(MasterSpec $specification): CompositeInterface
    {
        return new AndSpecification($this, $specification);
    }

    public function or(MasterSpec $specification): CompositeInterface
    {
        return new OrSpecification($this, $specification);
    }

    public function not(): NotInterface
    {
        return new Not($this);
    }
}
