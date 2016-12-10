<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Citation\IdentityInterface,
    Entity\Citation\Text,
    Event\CitationRegistered,
    Exception\InvalidArgumentException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Citation implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $text;

    public function __construct(IdentityInterface $identity, Text $text)
    {
        if (empty($text)) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->text = $text;
    }

    public static function register(
        IdentityInterface $identity,
        Text $text
    ): self {
        $self = new self($identity, $text);
        $self->record(new CitationRegistered($identity, $text));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function text(): Text
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return (string) $this->text;
    }
}
