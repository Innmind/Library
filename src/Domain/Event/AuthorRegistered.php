<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Author\{
    Identity,
    Name
};

final class AuthorRegistered
{
    private Identity $identity;
    private Name $name;

    public function __construct(Identity $identity, Name $name)
    {
        $this->identity = $identity;
        $this->name = $name;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function name(): Name
    {
        return $this->name;
    }
}
