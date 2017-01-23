<?php
declare(strict_types = 1);

namespace Domain\Command\HttpResource;

use Domain\Entity\{
    ResourceAuthor\IdentityInterface,
    Author\IdentityInterface as AuthorIdentity,
    HttpResource\IdentityInterface as ResourceIdentity
};

final class RegisterAuthor
{
    private $identity;
    private $author;
    private $resource;

    public function __construct(
        IdentityInterface $identity,
        AuthorIdentity $author,
        ResourceIdentity $resource
    ) {
        $this->identity = $identity;
        $this->author = $author;
        $this->resource = $resource;
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
}
