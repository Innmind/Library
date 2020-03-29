<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    HtmlPage\Identity,
    HostResource\Identity as RelationIdentity,
    Host\Identity as HostIdentity
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

final class RegisterHtmlPage
{
    private Identity $identity;
    private HostIdentity $host;
    private RelationIdentity $relation;
    private PathInterface $path;
    private QueryInterface $query;

    public function __construct(
        Identity $identity,
        HostIdentity $host,
        RelationIdentity $relation,
        PathInterface $path,
        QueryInterface $query
    ) {
        $this->identity = $identity;
        $this->host = $host;
        $this->relation = $relation;
        $this->path = $path;
        $this->query = $query;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function host(): HostIdentity
    {
        return $this->host;
    }

    public function relation(): RelationIdentity
    {
        return $this->relation;
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function query(): QueryInterface
    {
        return $this->query;
    }
}
