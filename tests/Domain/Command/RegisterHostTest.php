<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterHost,
    Entity\Host\Identity,
    Entity\Domain\Identity as DomainIdentity,
    Entity\DomainHost\Identity as RelationIdentity
};
use Innmind\Url\Authority\HostInterface;
use PHPUnit\Framework\TestCase;

class RegisterHostTest extends TestCase
{
    public function testInterface()
    {
        $command = new RegisterHost(
            $identity = $this->createMock(Identity::class),
            $domain = $this->createMock(DomainIdentity::class),
            $relation = $this->createMock(RelationIdentity::class),
            $host = $this->createMock(HostInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($domain, $command->domain());
        $this->assertSame($relation, $command->relation());
        $this->assertSame($host, $command->host());
    }
}
