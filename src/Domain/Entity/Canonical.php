<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Canonical\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\CanonicalCreated,
};
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};
use Innmind\TimeContinuum\PointInTime;

final class Canonical implements ContainsRecordedEvents
{
    use EventRecorder;

    private Identity $identity;
    private ResourceIdentity $canonical;
    private ResourceIdentity $resource;
    private PointInTime $foundAt;

    public function __construct(
        Identity $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource,
        PointInTime $foundAt
    ) {
        $this->identity = $identity;
        $this->canonical = $canonical;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public static function create(
        Identity $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource,
        PointInTime $foundAt
    ): self {
        $self = new self($identity, $canonical, $resource, $foundAt);
        $self->record(new CanonicalCreated(
            $identity,
            $canonical,
            $resource,
            $foundAt
        ));

        return $self;
    }

    public function identity(): Identity
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

    public function foundAt(): PointInTime
    {
        return $this->foundAt;
    }
}
