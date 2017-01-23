<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Reference\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\ReferenceCreated
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Reference implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $source;
    private $target;

    public function __construct(
        IdentityInterface $identity,
        ResourceIdentity $source,
        ResourceIdentity $target
    ) {
        $this->identity = $identity;
        $this->source = $source;
        $this->target = $target;
    }

    public static function create(
        IdentityInterface $identity,
        ResourceIdentity $source,
        ResourceIdentity $target
    ): self {
        $self = new self($identity, $source, $target);
        $self->record(new ReferenceCreated(
            $identity,
            $source,
            $target
        ));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function source(): ResourceIdentity
    {
        return $this->source;
    }

    public function target(): ResourceIdentity
    {
        return $this->target;
    }
}
