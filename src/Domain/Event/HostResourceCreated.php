<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\HostResource\IdentityInterface,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class HostResourceCreated
{
    private $identity;
    private $host;
    private $resource;
    private $foundAt;

    public function __construct(
        IdentityInterface $identity,
        HostIdentity $host,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->host = $host;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function host(): HostIdentity
    {
        return $this->host;
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
