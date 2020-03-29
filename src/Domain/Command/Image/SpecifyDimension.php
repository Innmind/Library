<?php
declare(strict_types = 1);

namespace Domain\Command\Image;

use Domain\Entity\Image\{
    Identity,
    Dimension
};

final class SpecifyDimension
{
    private Identity $identity;
    private Dimension $dimension;

    public function __construct(Identity $identity, Dimension $dimension)
    {
        $this->identity = $identity;
        $this->dimension = $dimension;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function dimension(): Dimension
    {
        return $this->dimension;
    }
}
