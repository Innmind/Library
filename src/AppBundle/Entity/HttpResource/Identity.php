<?php
declare(strict_types = 1);

namespace AppBundle\Entity\HttpResource;

use Domain\Entity\HttpResource\Identity as IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid;
use Innmind\Rest\Server\Identity as RestIdentity;

class Identity extends Uuid implements IdentityInterface, RestIdentity
{
}
