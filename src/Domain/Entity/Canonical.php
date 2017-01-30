<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\CanonicalCreated
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class Canonical implements ContainsRecordedEventsInterface
{
    use EventRecorder;

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

    public static function create(
        IdentityInterface $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
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
