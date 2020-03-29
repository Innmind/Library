<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\HostResource\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\HostResourceCreated,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};

final class HostResource implements ContainsRecordedEvents
{
    use EventRecorder;

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

    public static function create(
        Identity $identity,
        HostIdentity $host,
        ResourceIdentity $resource,
        PointInTime $foundAt
    ): self {
        $self = new self($identity, $host, $resource, $foundAt);
        $self->record(new HostResourceCreated(
            $identity,
            $host,
            $resource,
            $foundAt
        ));

        return $self;
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
