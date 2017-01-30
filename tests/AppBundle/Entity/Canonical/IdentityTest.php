<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Entity\Canonical;

use AppBundle\Entity\Canonical\Identity;
use Domain\Entity\Canonical\IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid as UuidIdentity;
use Innmind\Rest\Server\IdentityInterface as RestIdentity;
use Ramsey\Uuid\Uuid;

class IdentityTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $uuid = (string) Uuid::uuid4();
        $identity = new Identity($uuid);

        $this->assertInstanceOf(IdentityInterface::class, $identity);
        $this->assertInstanceOf(UuidIdentity::class, $identity);
        $this->assertInstanceOf(RestIdentity::class, $identity);
        $this->assertSame($uuid, (string) $identity);
    }
}
