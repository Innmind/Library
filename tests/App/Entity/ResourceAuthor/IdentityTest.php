<?php
declare(strict_types = 1);

namespace Tests\App\Entity\ResourceAuthor;

use App\Entity\ResourceAuthor\Identity;
use Domain\Entity\ResourceAuthor\Identity as IdentityInterface;
use Innmind\Neo4j\ONM\Identity\Uuid as UuidIdentity;
use Innmind\Rest\Server\Identity as RestIdentity;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase
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
