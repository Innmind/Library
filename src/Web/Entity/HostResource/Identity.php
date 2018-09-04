<?php
declare(strict_types = 1);

namespace Web\Entity\HostResource;

use Domain\Entity\HostResource\Identity as IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid;
use Innmind\Rest\Server\Identity as RestIdentity;

final class Identity extends Uuid implements IdentityInterface, RestIdentity
{
}
