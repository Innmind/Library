<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class CanonicalCreated
{
    private $identity;
    private $canonical;
    private $resource;
    private $foundAt;

    public function __construct(
        IdentityInterface $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->canonical = $canonical;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function canonical(): ResourceIdentity
    {
        return $this->canonical;
    }

    public function resource(): ResourceIdentity
    {
        return $this->resource;
    }

    public function foundAt(): PointInTimeInterface
    {
        return $this->foundAt;
    }
}
