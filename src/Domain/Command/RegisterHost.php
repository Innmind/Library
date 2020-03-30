<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    Host\Identity,
    Domain\Identity as DomainIdentity,
    DomainHost\Identity as RelationIdentity
};
use Innmind\Url\Authority\Host;

final class RegisterHost
{
    private Identity $identity;
    private DomainIdentity $domain;
    private RelationIdentity $relation;
    private Host $host;

    public function __construct(
        Identity $identity,
        DomainIdentity $domain,
        RelationIdentity $relation,
        Host $host
    ) {
        $this->identity = $identity;
        $this->domain = $domain;
        $this->relation = $relation;
        $this->host = $host;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function domain(): DomainIdentity
    {
        return $this->domain;
    }

    public function relation(): RelationIdentity
    {
        return $this->relation;
    }

    public function host(): Host
    {
        return $this->host;
    }
}
