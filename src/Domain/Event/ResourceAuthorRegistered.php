<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\ResourceAuthor\Identity,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTime;

final class ResourceAuthorRegistered
{
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
