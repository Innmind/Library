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

final class Canonical implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $canonical;
    private $resource;

    public function __construct(
        IdentityInterface $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource
    ) {
        $this->identity = $identity;
        $this->canonical = $canonical;
        $this->resource = $resource;
    }

    public static function create(
        IdentityInterface $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource
    ): self {
        $self = new self($identity, $canonical, $resource);
        $self->record(new CanonicalCreated(
            $identity,
            $canonical,
            $resource
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
}
