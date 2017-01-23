<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Entity\Image;

use AppBundle\Entity\{
    Image\Identity,
    HttpResource\Identity as HttpResourceIdentity
};
use Domain\Entity\Image\IdentityInterface;
use Innmind\Rest\Server\IdentityInterface as RestIdentity;
use Ramsey\Uuid\Uuid;

class IdentityTest extends \PHPUnit_Framework_TestCase
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
