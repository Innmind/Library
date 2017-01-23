<?php
declare(strict_types = 1);

namespace Domain\Command\Image;

use Domain\Entity\Image\{
    IdentityInterface,
    Dimension
};

final class SpecifyDimension
{
    private $identity;
    private $dimension;

    public function __construct(IdentityInterface $identity, Dimension $dimension)
    {
        $this->identity = $identity;
        $this->dimension = $dimension;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function dimension(): Dimension
    {
        return $this->dimension;
    }
}
