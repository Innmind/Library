<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\HostResource\IdentityInterface,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\HostResourceCreated
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class HostResource implements ContainsRecordedEventsInterface
{
    use EventRecorder;

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

    public static function create(
        IdentityInterface $identity,
        HostIdentity $host,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
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
