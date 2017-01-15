<?php
declare(strict_types = 1);

namespace AppBundle\Entity\Citation;

use AppBundle\Exception\InvalidArgumentException;
use Domain\Entity\Citation\IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid;
use Innmind\Rest\Server\IdentityInterface as RestIdentity;

final class Identity extends Uuid implements IdentityInterface, RestIdentity
{
}
