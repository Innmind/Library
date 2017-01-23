<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\ResourceAuthor\IdentityInterface,
    Entity\Author\IdentityInterface as AuthorIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\ResourceAuthorRegistered
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};

final class ResourceAuthor implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $author;
    private $resource;
    private $asOf;

    public function __construct(
        IdentityInterface $identity,
        AuthorIdentity $author,
        ResourceIdentity $resource,
        PointInTimeInterface $asOf
    ) {
        $this->identity = $identity;
        $this->author = $author;
        $this->resource = $resource;
        $this->asOf = $asOf;
    }

    public static function register(
        IdentityInterface $identity,
        AuthorIdentity $author,
        ResourceIdentity $resource,
        PointInTimeInterface $asOf
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

    public function identity(): IdentityInterface
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

    public function asOf(): PointInTimeInterface
    {
        return $this->asOf;
    }
}
