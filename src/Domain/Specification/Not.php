<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\{
    Specification,
    Not as NotInterface,
};

class Not implements NotInterface
{
    use Composable;

    private $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    /**
     * {@inheritdoc}
     */
    public function specification(): Specification
    {
        return $this->specification;
    }
}
