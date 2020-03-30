<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\ResourceAuthor\Identity,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\ResourceAuthorRegistered,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};

final class ResourceAuthor implements ContainsRecordedEvents
{
    use EventRecorder;

    private Identity $identity;
    private AuthorIdentity $author;
    private ResourceIdentity $resource;
    private PointInTime $asOf;

    public function __construct(
        Identity $identity,
        AuthorIdentity $author,
        ResourceIdentity $resource,
        PointInTime $asOf
    ) {
        $this->identity = $identity;
        $this->author = $author;
        $this->resource = $resource;
        $this->asOf = $asOf;
    }

    public static function register(
        Identity $identity,
        AuthorIdentity $author,
        ResourceIdentity $resource,
        PointInTime $asOf
    ): self {
        $self = new self($identity, $author, $resource, $asOf);
        $self->record(new ResourceAuthorRegistered(
            $identity,
            $author,
            $resource,
            $asOf
        ));

        return $self;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function author(): AuthorIdentity
    {
        return $this->author;
    }

    public function resource(): ResourceIdentity
    {
        return $this->resource;
    }

    public function asOf(): PointInTime
    {
        return $this->asOf;
    }
}
