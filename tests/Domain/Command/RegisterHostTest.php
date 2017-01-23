<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterHost,
    Entity\Host\IdentityInterface,
    Entity\Domain\IdentityInterface as DomainIdentity,
    Entity\DomainHost\IdentityInterface as RelationIdentity
};
use Innmind\Url\Authority\HostInterface;

class RegisterHostTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new RegisterHost(
            $identity = $this->createMock(IdentityInterface::class),
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
