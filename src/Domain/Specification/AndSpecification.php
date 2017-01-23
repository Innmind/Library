<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\{
    SpecificationInterface,
    CompositeInterface,
    Operator
};

class AndSpecification implements CompositeInterface
{
    use Composable;

    private $left;
    private $right;
    private $operator;

    public function __construct(
        SpecificationInterface $left,
        SpecificationInterface $right
    ) {
        $this->left = $left;
        $this->right = $right;
        $this->operator = new Operator(Operator::AND);
    }

    /**
     * {@inheritdoc}
     */
    public function left(): SpecificationInterface
    {
        return $this->left;
    }

    /**
     * {@inheritdoc}
     */
    public function right(): SpecificationInterface
    {
        return $this->right;
    }

    /**
     * {@inheritdoc}
     */
    public function operator(): Operator
    {
        return $this->operator;
    }
}
