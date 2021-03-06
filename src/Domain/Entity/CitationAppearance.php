<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\CitationAppearanceRegistered,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};

final class CitationAppearance implements ContainsRecordedEvents
{
    use EventRecorder;

    private Identity $identity;
    private CitationIdentity $citation;
    private ResourceIdentity $resource;
    private PointInTime $foundAt;

    public function __construct(
        Identity $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource,
        PointInTime $foundAt
    ) {
        $this->identity = $identity;
        $this->citation = $citation;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public static function register(
        Identity $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource,
        PointInTime $foundAt
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

    public function identity(): Identity
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

    public function foundAt(): PointInTime
    {
        return $this->foundAt;
    }
}
