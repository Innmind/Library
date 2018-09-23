<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterDomain,
    Entity\Domain\Identity
};
use Innmind\Url\Authority\HostInterface;
use PHPUnit\Framework\TestCase;

class RegisterDomainTest extends TestCase
{
    public function testInterface()
    {
        $command = new RegisterDomain(
            $identity = $this->createMock(Identity::class),
            $host = $this->createMock(HostInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($host, $command->host());
    }
}
