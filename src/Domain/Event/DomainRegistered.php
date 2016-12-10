<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Domain\{
    IdentityInterface,
    Name,
    TopLevelDomain
};

final class DomainRegistered
{
    private $identity;
    private $name;
    private $tld;

    public function __construct(
        IdentityInterface $identity,
        Name $name,
        TopLevelDomain $tld
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->tld = $tld;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function tld(): TopLevelDomain
    {
        return $this->tld;
    }
}
