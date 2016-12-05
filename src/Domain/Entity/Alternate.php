<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\Alternate\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\AlternateCreated,
    Exception\InvalidArgumentException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class Alternate implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $resource;
    private $alternate;
    private $language;

    public function __construct(
        IdentityInterface $identity,
        ResourceIdentity $resource,
        ResourceIdentity $alternate,
        string $language
    ) {
        if (empty($language)) {
            throw new InvalidArgumentException;
        }

        $this->identity = $identity;
        $this->resource = $resource;
        $this->alternate = $alternate;
        $this->language = $language;
    }

    public static function create(
        IdentityInterface $identity,
        ResourceIdentity $resource,
        ResourceIdentity $alternate,
        string $language
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

    public function identity(): IdentityInterface
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

    public function language(): string
    {
        return $this->language;
    }
}
