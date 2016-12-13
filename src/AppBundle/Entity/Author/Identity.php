<?php
declare(strict_types = 1);

namespace AppBundle\Entity\Author;

use AppBundle\Exception\InvalidArgumentException;
use Domain\Entity\Author\IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid;

final class Identity extends Uuid implements IdentityInterface
{
}
