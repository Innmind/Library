<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class CitationAppearanceRegistered
{
    private $identity;
    private $citation;
    private $resource;
    private $foundAt;

    public function __construct(
        Identity $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->citation = $citation;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function citation(): CitationIdentity
    {
        return $this->citation;
    }

    public function resource(): ResourceIdentity
    {
        return $this->resource;
    }

    public function foundAt(): PointInTimeInterface
    {
        return $this->foundAt;
    }
}
