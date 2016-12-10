<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterAlternateResource,
    Entity\Alternate\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Model\Language
};

class RegisterAlternateResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new RegisterAlternateResource(
            $identity = $this->createMock(IdentityInterface::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $alternate = $this->createMock(ResourceIdentity::class),
            $language = new Language('fr')
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($resource, $command->resource());
        $this->assertSame($alternate, $command->alternate());
        $this->assertSame($language, $command->language());
    }
}
