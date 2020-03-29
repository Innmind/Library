<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterDomain,
    Entity\Domain\Identity
};
use Innmind\Url\Authority\Host;
use PHPUnit\Framework\TestCase;

class RegisterDomainTest extends TestCase
{
    public function testInterface()
    {
        $command = new RegisterDomain(
            $identity = $this->createMock(Identity::class),
            $host = Host::none()
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($host, $command->host());
    }
}
