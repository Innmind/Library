<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Domain\IdentityInterface,
    Entity\Domain\Name,
    Event\DomainRegistered,
    Exception\InvalidArgumentException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Domain implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $name;
    private $tld;

    public function __construct(
        IdentityInterface $identity,
        Name $name,
        string $tld
    ) {
        if (empty($tld)) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->name = $name;
        $this->tld = $tld;
    }

    public static function register(
        IdentityInterface $identity,
        Name $name,
        string $tld
    ): self {
        $self = new self($identity, $name, $tld);
        $self->record(new DomainRegistered($identity, $name, $tld));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function tld(): string
    {
        return $this->tld;
    }

    public function __toString(): string
    {
        return $this->name.'.'.$this->tld;
    }
}
