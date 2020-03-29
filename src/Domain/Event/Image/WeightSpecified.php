<?php
declare(strict_types = 1);

namespace Domain\Event\Image;

use Domain\Entity\Image\{
    Identity,
    Weight
};

final class WeightSpecified
{
    private Identity $identity;
    private Weight $weight;

    public function __construct(Identity $identity, Weight $weight)
    {
        $this->identity = $identity;
        $this->weight = $weight;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function weight(): Weight
    {
        return $this->weight;
    }
}
