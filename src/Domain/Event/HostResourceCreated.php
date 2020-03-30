<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\HostResource\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTime;

final class HostResourceCreated
{
    private Identity $identity;
    private HostIdentity $host;
    private ResourceIdentity $resource;
    private PointInTime $foundAt;

    public function __construct(
        Identity $identity,
        HostIdentity $host,
        ResourceIdentity $resource,
        PointInTime $foundAt
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

    public function foundAt(): PointInTime
    {
        return $this->foundAt;
    }
}
