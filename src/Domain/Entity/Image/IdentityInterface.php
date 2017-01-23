<?php
declare(strict_types = 1);

namespace Domain\Entity\Image;

use Domain\Entity\HttpResource\IdentityInterface as ResourceIdentity;

interface IdentityInterface extends ResourceIdentity
{
    public function __toString(): string;
}
