<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\DomainHost\Identity,
    Entity\Domain\Identity as DomainIdentity,
    Entity\Host\Identity as HostIdentity
};
use Innmind\TimeContinuum\PointInTime;

final class DomainHostCreated
{
    private Identity $identity;
    private DomainIdentity $domain;
    private HostIdentity $host;
    private PointInTime $foundAt;

    public function __construct(
        Identity $identity,
        DomainIdentity $domain,
        HostIdentity $host,
        PointInTime $foundAt
    ) {
        $this->identity = $identity;
        $this->domain = $domain;
        $this->host = $host;
        $this->foundAt = $foundAt;
    }

    public function identity(): Identity
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

    public function foundAt(): PointInTime
    {
        return $this->foundAt;
    }
}
