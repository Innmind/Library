<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    Host\Identity,
    Domain\Identity as DomainIdentity,
    DomainHost\Identity as RelationIdentity
};
use Innmind\Url\Authority\HostInterface;

final class RegisterHost
{
    private $identity;
    private $domain;
    private $relation;
    private $host;

    public function __construct(
        Identity $identity,
        DomainIdentity $domain,
        RelationIdentity $relation,
        HostInterface $host
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

    public function host(): HostInterface
    {
        return $this->host;
    }
}
