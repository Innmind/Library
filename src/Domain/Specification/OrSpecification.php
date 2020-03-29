<?php
declare(strict_types = 1);

namespace Domain\Specification;

use Innmind\Specification\{
    Specification,
    Composite,
    Operator,
};

class OrSpecification implements Composite
{
    use Composable;

    private Specification $left;
    private Specification $right;
    private Operator $operator;

    public function __construct(
        Specification $left,
        Specification $right
    ) {
        $this->left = $left;
        $this->right = $right;
        $this->operator = Operator::or();
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
