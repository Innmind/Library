<?php
declare(strict_types = 1);

namespace Domain\Event\Image;

use Domain\Entity\Image\IdentityInterface;

final class WeightSpecified
{
    private $identity;
    private $weight;

    public function __construct(IdentityInterface $identity, int $weight)
    {
        $this->identity = $identity;
        $this->weight = $weight;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function weight(): int
    {
        return $this->weight;
    }
}
