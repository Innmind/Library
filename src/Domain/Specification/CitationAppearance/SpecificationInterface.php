<?php
declare(strict_types = 1);

namespace Domain\Specification\CitationAppearance;

use Domain\Entity\CitationAppearance as Entity;
use Innmind\Specification\SpecificationInterface as ParentSpec;

interface SpecificationInterface extends ParentSpec
{
    public function isSatisfiedBy(Entity $appearance): bool;
}
