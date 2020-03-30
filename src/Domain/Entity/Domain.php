<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Domain\Identity,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain,
    Event\DomainRegistered,
};
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};

final class Domain implements ContainsRecordedEvents
{
    use EventRecorder;

    private Identity $identity;
    private Name $name;
    private TopLevelDomain $tld;

    public function __construct(
        Identity $identity,
        Name $name,
        TopLevelDomain $tld
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->tld = $tld;
    }

    public static function register(
        Identity $identity,
        Name $name,
        TopLevelDomain $tld
    ): self {
        $self = new self($identity, $name, $tld);
        $self->record(new DomainRegistered($identity, $name, $tld));

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

    public function tld(): TopLevelDomain
    {
        return $this->tld;
    }

    public function __toString(): string
    {
        return $this->name.'.'.$this->tld;
    }
}
