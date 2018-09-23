<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Image\Identity;
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

final class ImageRegistered
{
    private $identity;
    private $path;
    private $query;

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
