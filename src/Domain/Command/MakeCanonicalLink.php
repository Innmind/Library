<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    Canonical\Identity,
    HttpResource\Identity as ResourceIdentity
};

final class MakeCanonicalLink
{
    private Identity $identity;
    private ResourceIdentity $canonical;
    private ResourceIdentity $resource;

    public function __construct(
        Identity $identity,
        ResourceIdentity $canonical,
        ResourceIdentity $resource
    ) {
        $this->identity = $identity;
        $this->canonical = $canonical;
        $this->resource = $resource;
    }

    public function identity(): Identity
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
