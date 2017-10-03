<?php
declare(strict_types = 1);

namespace AppBundle\Entity\Image;

use AppBundle\Entity\HttpResource\Identity as HttpResourceIdentity;
use Domain\Entity\Image\Identity as IdentityInterface;
use Innmind\Rest\Server\Identity as RestIdentity;

final class Identity extends HttpResourceIdentity implements IdentityInterface, RestIdentity
{
}
