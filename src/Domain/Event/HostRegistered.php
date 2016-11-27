<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Host\IdentityInterface;

final class HostRegistered
{
    private $identity;
    private $name;

    public function __construct(
        IdentityInterface $identity,
        string $name
    ) {
        $this->identity = $identity;
        $this->name = $name;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): string
    {
        return $this->name;
    }
}
