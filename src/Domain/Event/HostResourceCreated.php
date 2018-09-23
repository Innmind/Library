<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\HostResource\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class HostResourceCreated
{
    private $identity;
    private $host;
    private $resource;
    private $foundAt;

    public function __construct(
        Identity $identity,
        HostIdentity $host,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->host = $host;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public function identity(): Identity
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
