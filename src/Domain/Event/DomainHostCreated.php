<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\DomainHost\IdentityInterface,
    Entity\Domain\IdentityInterface as DomainIdentity,
    Entity\Host\IdentityInterface as HostIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class DomainHostCreated
{
    private $identity;
    private $domain;
    private $host;
    private $foundAt;

    public function __construct(
        IdentityInterface $identity,
        DomainIdentity $domain,
        HostIdentity $host,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->domain = $domain;
        $this->host = $host;
        $this->foundAt = $foundAt;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function domain(): DomainIdentity
    {
        return $this->domain;
    }

    public function host(): HostIdentity
    {
        return $this->host;
    }

    public function foundAt(): PointInTimeInterface
    {
        return $this->foundAt;
    }
}
