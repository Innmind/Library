<?php
declare(strict_types = 1);

namespace Domain\Entity\HtmlPage;

use Domain\Entity\HttpResource\Identity as ResourceIdentity;

interface Identity extends ResourceIdentity
{
    public function __toString(): string;
}
