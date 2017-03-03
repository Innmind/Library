<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\ReferResource,
    Entity\Reference\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use PHPUnit\Framework\TestCase;

class ReferResourceTest extends TestCase
{
    public function testInterface()
    {
        $command = new ReferResource(
            $identity = $this->createMock(IdentityInterface::class),
            $source = $this->createMock(ResourceIdentity::class),
            $target = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($source, $command->source());
        $this->assertSame($target, $command->target());
    }
}
