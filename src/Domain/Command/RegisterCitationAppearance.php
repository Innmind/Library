<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    CitationAppearance\IdentityInterface,
    Citation\IdentityInterface as CitationIdentity,
    HttpResource\IdentityInterface as ResourceIdentity
};

final class RegisterCitationAppearance
{
    private $identity;
    private $citation;
    private $resource;

    public function __construct(
        IdentityInterface $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource
    ) {
        $this->identity = $identity;
        $this->citation = $citation;
        $this->resource = $resource;
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
}
