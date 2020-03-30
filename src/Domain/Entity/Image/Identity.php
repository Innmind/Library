<?php
declare(strict_types = 1);

namespace Domain\Entity\Image;

use Domain\Entity\HttpResource\Identity as ResourceIdentity;

interface Identity extends ResourceIdentity
{
    public function toString(): string;
}
