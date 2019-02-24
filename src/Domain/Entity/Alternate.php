<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Alternate\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\AlternateCreated,
    Model\Language,
};
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};

final class Alternate implements ContainsRecordedEvents
{
    use EventRecorder;

    private $identity;
    private $resource;
    private $alternate;
    private $language;

    public function __construct(
        Identity $identity,
        ResourceIdentity $resource,
        ResourceIdentity $alternate,
        Language $language
    ) {
        $this->identity = $identity;
        $this->resource = $resource;
        $this->alternate = $alternate;
        $this->language = $language;
    }

    public static function create(
        Identity $identity,
        ResourceIdentity $resource,
        ResourceIdentity $alternate,
        Language $language
    ): self {
        $self = new self($identity, $resource, $alternate, $language);
        $self->record(new AlternateCreated(
            $identity,
            $resource,
            $alternate,
            $language
        ));

        return $self;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function resource(): ResourceIdentity
    {
        return $this->resource;
    }

    public function alternate(): ResourceIdentity
    {
        return $this->alternate;
    }

    public function language(): Language
    {
        return $this->language;
    }
}
