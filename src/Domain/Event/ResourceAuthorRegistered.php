<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\ResourceAuthor\IdentityInterface,
    Entity\Author\IdentityInterface as AuthorIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class ResourceAuthorRegistered
{
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
