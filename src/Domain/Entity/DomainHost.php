<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\DomainHost\Identity,
    Entity\Domain\Identity as DomainIdentity,
    Entity\Host\Identity as HostIdentity,
    Event\DomainHostCreated,
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};

final class DomainHost implements ContainsRecordedEvents
{
    use EventRecorder;

    private Identity $identity;
    private DomainIdentity $domain;
    private HostIdentity $host;
    private PointInTimeInterface $foundAt;

    public function __construct(
        Identity $identity,
        DomainIdentity $domain,
        HostIdentity $host,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->domain = $domain;
        $this->host = $host;
        $this->foundAt = $foundAt;
    }

    public static function create(
        Identity $identity,
        DomainIdentity $domain,
        HostIdentity $host,
        PointInTimeInterface $foundAt
    ): self {
        $self = new self($identity, $domain, $host, $foundAt);
        $self->record(new DomainHostCreated(
            $identity,
            $domain,
            $host,
            $foundAt
        ));

        return $self;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function domain(): DomainIdentity
    {
        return $this->domain;
    }

    public function host(): HostIdentity
    {
        return $this->host;
    }

    public function foundAt(): PointInTimeInterface
    {
        return $this->foundAt;
    }
}
