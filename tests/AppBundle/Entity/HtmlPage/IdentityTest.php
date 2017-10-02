<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Entity\HtmlPage;

use AppBundle\Entity\{
    HtmlPage\Identity,
    HttpResource\Identity as HttpResourceIdentity
};
use Domain\Entity\HtmlPage\IdentityInterface;
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
        $this->assertInstanceOf(HttpResourceIdentity::class, $identity);
        $this->assertInstanceOf(RestIdentity::class, $identity);
        $this->assertSame($uuid, (string) $identity);
    }
}
