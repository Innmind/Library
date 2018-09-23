<?php
declare(strict_types = 1);

namespace Domain\Command\Citation;

use Domain\Entity\{
    CitationAppearance\Identity,
    Citation\Identity as CitationIdentity,
    HttpResource\Identity as ResourceIdentity
};

final class RegisterAppearance
{
    private $identity;
    private $citation;
    private $resource;

    public function __construct(
        Identity $identity,
        CitationIdentity $citation,
        ResourceIdentity $resource
    ) {
        $this->identity = $identity;
        $this->citation = $citation;
        $this->resource = $resource;
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
}
