<?php
declare(strict_types = 1);

namespace Domain\Specification\Author;

use Domain\{
    Specification\AndSpecification as ParentSpec,
    Entity\Author,
};

final class AndSpecification extends ParentSpec implements Specification
{
    use Composable;

    public function __construct(
        Specification $left,
        Specification $right
    ) {
        parent::__construct($left, $right);
    }

    public function isSatisfiedBy(Author $author): bool
    {
        return $this->left()->isSatisfiedBy($author) &&
            $this->right()->isSatisfiedBy($author);
    }
}
