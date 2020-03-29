<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Image\Identity;
use Innmind\Url\{
    Path,
    Query
};

final class ImageRegistered
{
    private Identity $identity;
    private Path $path;
    private Query $query;

    public function __construct(
        Identity $identity,
        Path $path,
        Query $query
    ) {
        $this->identity = $identity;
        $this->path = $path;
        $this->query = $query;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function path(): Path
    {
        return $this->path;
    }

    public function query(): Query
    {
        return $this->query;
    }
}
