<?php
declare(strict_types = 1);

namespace Domain\Command\HttpResource;

use Domain\Entity\{
    ResourceAuthor\Identity,
    Author\Identity as AuthorIdentity,
    HttpResource\Identity as ResourceIdentity
};

final class RegisterAuthor
{
    private Identity $identity;
    private AuthorIdentity $author;
    private ResourceIdentity $resource;

    public function __construct(
        Identity $identity,
        AuthorIdentity $author,
        ResourceIdentity $resource
    ) {
        $this->identity = $identity;
        $this->author = $author;
        $this->resource = $resource;
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
}
