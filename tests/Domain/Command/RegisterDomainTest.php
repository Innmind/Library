<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterDomain,
    Entity\Domain\IdentityInterface
};
use Innmind\Url\Authority\HostInterface;

class RegisterDomainTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new RegisterDomain(
            $identity = $this->createMock(IdentityInterface::class),
            $host = $this->createMock(HostInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($host, $command->host());
    }
}
