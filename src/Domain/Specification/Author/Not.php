<?php
declare(strict_types = 1);

namespace Domain\Specification\Author;

use Domain\{
    Specification\Not as ParentSpec,
    Entity\Author
};

final class Not extends ParentSpec implements Specification
{
    use Composable;

    public function __construct(Specification $specification)
    {
        parent::__construct($specification);
    }

    public function isSatisfiedBy(Author $author): bool
    {
        return !$this->specification()->isSatisfiedBy($author);
    }
}
