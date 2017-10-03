<?php
declare(strict_types = 1);

namespace Domain\Specification\Author;

use Domain\Entity\Author;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface Specification extends ParentSpec
{
    public function isSatisfiedBy(Author $author): bool;
}
