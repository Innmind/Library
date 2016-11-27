<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\{
    SpecificationInterface,
    NotInterface
};

class Not implements NotInterface
{
    use Composable;

    private $specification;

    public function __construct(SpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * {@inheritdoc}
     */
    public function specification(): SpecificationInterface
    {
        return $this->specification;
    }
}
