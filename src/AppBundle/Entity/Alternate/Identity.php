<?php
declare(strict_types = 1);

namespace AppBundle\Entity\Alternate;

use Domain\Entity\Alternate\Identity as IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid;
use Innmind\Rest\Server\Identity as RestIdentity;

final class Identity extends Uuid implements IdentityInterface, RestIdentity
{
}
