<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\CitationAppearance\IdentityInterface,
    Entity\Citation\IdentityInterface as CitationIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\CitationAppearanceRegistered
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class CitationAppearance implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $citation;
    private $resource;
    private $foundAt;

    public function __construct(
        IdentityInterface $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->citation = $citation;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public static function register(
        IdentityInterface $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
    ): self {
        $self = new self($identity, $citation, $resource, $foundAt);
        $self->record(new CitationAppearanceRegistered(
            $identity,
            $citation,
            $resource,
            $foundAt
        ));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function citation(): CitationIdentity
    {
        return $this->citation;
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
