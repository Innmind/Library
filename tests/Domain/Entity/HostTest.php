<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Host,
    Entity\Host\Identity,
    Entity\Host\Name,
    Event\HostRegistered
};
use Innmind\EventBus\ContainsRecordedEventsInterface;
use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    public function testInstanciation()
    {
        $host = new Host(
            $identity = $this->createMock(Identity::class),
            $name = new Name('www.example.com')
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $host);
        $this->assertSame($identity, $host->identity());
        $this->assertSame($name, $host->name());
        $this->assertSame('www.example.com', (string) $host);
        $this->assertCount(0, $host->recordedEvents());
    }

    public function testRegister()
    {
        $host = Host::register(
            $identity = $this->createMock(Identity::class),
            $name = new Name('www.example.com')
        );

        $this->assertInstanceOf(Host::class, $host);
        $this->assertCount(1, $host->recordedEvents());
        $this->assertInstanceOf(
            HostRegistered::class,
            $host->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $host->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $name,
            $host->recordedEvents()->current()->name()
        );
    }
}
