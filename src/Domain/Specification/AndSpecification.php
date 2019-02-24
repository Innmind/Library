<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\{
    Specification,
    Composite,
    Operator,
};

class AndSpecification implements Composite
{
    use Composable;

    private $left;
    private $right;
    private $operator;

    public function __construct(
        Specification $left,
        Specification $right
    ) {
        $this->left = $left;
        $this->right = $right;
        $this->operator = Operator::and();
    }

    /**
     * {@inheritdoc}
     */
    public function left(): Specification
    {
        return $this->left;
    }

    /**
     * {@inheritdoc}
     */
    public function right(): Specification
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
