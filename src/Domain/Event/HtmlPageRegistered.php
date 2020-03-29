<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\HtmlPage\Identity;
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

final class HtmlPageRegistered
{
    private Identity $identity;
    private PathInterface $path;
    private QueryInterface $query;

    public function __construct(
        Identity $identity,
        PathInterface $path,
        QueryInterface $query
    ) {
        $this->identity = $identity;
        $this->path = $path;
        $this->query = $query;
    }

    public function identity(): Identity
    {
        return $this->identity;
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
