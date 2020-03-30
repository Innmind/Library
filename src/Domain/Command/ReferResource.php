<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    Reference\Identity,
    HttpResource\Identity as ResourceIdentity
};

final class ReferResource
{
    private Identity $identity;
    private ResourceIdentity $source;
    private ResourceIdentity $target;

    public function __construct(
        Identity $identity,
        ResourceIdentity $source,
        ResourceIdentity $target
    ) {
        $this->identity = $identity;
        $this->source = $source;
        $this->target = $target;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function source(): ResourceIdentity
    {
        return $this->source;
    }

    public function target(): ResourceIdentity
    {
        return $this->target;
    }
}
