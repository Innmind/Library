<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    HtmlPage\IdentityInterface,
    HostResource\IdentityInterface as RelationIdentity,
    Host\IdentityInterface as HostIdentity
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

final class RegisterHtmlPage
{
    private $identity;
    private $host;
    private $relation;
    private $path;
    private $query;

    public function __construct(
        IdentityInterface $identity,
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

    public function identity(): IdentityInterface
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
