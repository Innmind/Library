<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\Citation;

use Domain\{
    Command\Citation\RegisterAppearance,
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use PHPUnit\Framework\TestCase;

class RegisterAppearanceTest extends TestCase
{
    public function testInterface()
    {
        $command = new RegisterAppearance(
            $identity = $this->createMock(Identity::class),
            $citation = $this->createMock(CitationIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($citation, $command->citation());
        $this->assertSame($resource, $command->resource());
    }
}
