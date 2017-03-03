<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\MakeCanonicalLink,
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use PHPUnit\Framework\TestCase;

class MakeCanonicalLinkTest extends TestCase
{
    public function testInterface()
    {
        $command = new MakeCanonicalLink(
            $identity = $this->createMock(IdentityInterface::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($canonical, $command->canonical());
        $this->assertSame($resource, $command->resource());
    }
}
