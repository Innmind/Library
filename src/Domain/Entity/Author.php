<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Author\IdentityInterface,
    Event\AuthorRegistered,
    Exception\InvalidArgumentException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Author implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $name;

    public function __construct(IdentityInterface $identity, string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->name = $name;
    }

    public static function register(
        IdentityInterface $identity,
        string $name
    ): self {
        $self = new self($identity, $name);
        $self->record(new AuthorRegistered($identity, $name));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
