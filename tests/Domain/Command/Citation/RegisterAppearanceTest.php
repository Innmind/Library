<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\Citation;

use Domain\{
    Command\Citation\RegisterAppearance,
    Entity\CitationAppearance\IdentityInterface,
    Entity\Citation\IdentityInterface as CitationIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};

class RegisterAppearanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new RegisterAppearance(
            $identity = $this->createMock(IdentityInterface::class),
            $citation = $this->createMock(CitationIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($citation, $command->citation());
        $this->assertSame($resource, $command->resource());
    }
}
