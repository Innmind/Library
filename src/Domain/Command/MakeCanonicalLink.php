<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    Canonical\IdentityInterface,
    HttpResource\IdentityInterface as ResourceIdentity
};

final class MakeCanonicalLink
{
    private $identity;
    private $canonical;
    private $resource;

    public function __construct(
        IdentityInterface $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource
    ) {
        $this->identity = $identity;
        $this->canonical = $canonical;
        $this->resource = $resource;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function canonical(): ResourceIdentity
    {
        return $this->canonical;
    }

    public function resource(): ResourceIdentity
    {
        return $this->resource;
    }
}
