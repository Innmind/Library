<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\{
    Reference\IdentityInterface,
    HttpResource\IdentityInterface as ResourceIdentity
};

final class ReferResource
{
    private $identity;
    private $source;
    private $target;

    public function __construct(
        IdentityInterface $identity,
        ResourceIdentity $source,
        ResourceIdentity $target
    ) {
        $this->identity = $identity;
        $this->source = $source;
        $this->target = $target;
    }

    public function identity(): IdentityInterface
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
