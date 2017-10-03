<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Host\Identity,
    Entity\Host\Name,
    Event\HostRegistered
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Host implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $name;

    public function __construct(
        Identity $identity,
        Name $name
    ) {
        $this->identity = $identity;
        $this->name = $name;
    }

    public static function register(
        Identity $identity,
        Name $name
    ): self {
        $self = new self($identity, $name);
        $self->record(new HostRegistered($identity, $name));

        return $self;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
