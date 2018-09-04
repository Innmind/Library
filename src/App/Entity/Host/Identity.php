<?php
declare(strict_types = 1);

namespace App\Entity\Host;

use Domain\Entity\Host\Identity as IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid;
use Innmind\Rest\Server\Identity as RestIdentity;

final class Identity extends Uuid implements IdentityInterface, RestIdentity
{
}
