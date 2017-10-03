<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\ResourceAuthor\Identity,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class ResourceAuthorRegistered
{
    private $identity;
    private $author;
    private $resource;
    private $asOf;

    public function __construct(
        Identity $identity,
        AuthorIdentity $author,
        ResourceIdentity $resource,
        PointInTimeInterface $asOf
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

    public function asOf(): PointInTimeInterface
    {
        return $this->asOf;
    }
}
