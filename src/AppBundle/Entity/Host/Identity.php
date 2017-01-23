<?php
declare(strict_types = 1);

namespace AppBundle\Entity\Host;

use Domain\Entity\Host\IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid;
use Innmind\Rest\Server\IdentityInterface as RestIdentity;

final class Identity extends Uuid implements IdentityInterface, RestIdentity
{
}
