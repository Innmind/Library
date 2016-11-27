<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Domain\IdentityInterface;

final class DomainRegistered
{
    private $identity;
    private $name;
    private $tld;

    public function __construct(
        IdentityInterface $identity,
        string $name,
        string $tld
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->tld = $tld;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function tld(): string
    {
        return $this->tld;
    }
}
