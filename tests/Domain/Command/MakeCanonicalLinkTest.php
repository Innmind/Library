<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\MakeCanonicalLink,
    Entity\Canonical\Identity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use PHPUnit\Framework\TestCase;

class MakeCanonicalLinkTest extends TestCase
{
    public function testInterface()
    {
        $command = new MakeCanonicalLink(
            $identity = $this->createMock(Identity::class),
            $canonical = $this->createMock(ResourceIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($canonical, $command->canonical());
        $this->assertSame($resource, $command->resource());
    }
}
