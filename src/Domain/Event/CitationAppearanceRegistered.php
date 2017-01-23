<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\CitationAppearance\IdentityInterface,
    Entity\Citation\IdentityInterface as CitationIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

final class CitationAppearanceRegistered
{
    private $identity;
    private $citation;
    private $resource;
    private $foundAt;

    public function __construct(
        IdentityInterface $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource,
        PointInTimeInterface $foundAt
    ) {
        $this->identity = $identity;
        $this->citation = $citation;
        $this->resource = $resource;
        $this->foundAt = $foundAt;
    }

    public function identity(): IdentityInterface
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
