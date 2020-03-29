<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Domain\{
    Identity,
    Name,
    TopLevelDomain
};

final class DomainRegistered
{
    private Identity $identity;
    private Name $name;
    private TopLevelDomain $tld;

    public function __construct(
        Identity $identity,
        Name $name,
        TopLevelDomain $tld
    ) {
        $this->identity = $identity;
        $this->name = $name;
        $this->tld = $tld;
    }

    public function identity(): Identity
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
