<?php
declare(strict_types = 1);

namespace AppBundle\Entity\Image;

use AppBundle\Entity\HttpResource\Identity as HttpResourceIdentity;
use Domain\Entity\Image\IdentityInterface;
use Innmind\Rest\Server\IdentityInterface as RestIdentity;

final class Identity extends HttpResourceIdentity implements IdentityInterface, RestIdentity
{
}
