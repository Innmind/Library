<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Host\{
    IdentityInterface,
    Name
};

final class HostRegistered
{
    private $identity;
    private $name;

    public function __construct(
        IdentityInterface $identity,
        Name $name
    ) {
        $this->identity = $identity;
        $this->name = $name;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): Name
    {
        return $this->name;
    }
}
