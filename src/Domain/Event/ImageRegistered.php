<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\Entity\Image\IdentityInterface;
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
        IdentityInterface $identity,
        PathInterface $path,
        QueryInterface $query
    ) {
        $this->identity = $identity;
        $this->path = $path;
        $this->query = $query;
    }

    public function identity(): IdentityInterface
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
