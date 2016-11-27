<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\Host,
    Entity\Host\IdentityInterface,
    Event\HostRegistered
};
use Innmind\EventBus\ContainsRecordedEventsInterface;

class HostTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciation()
    {
        $host = new Host(
            $identity = $this->createMock(IdentityInterface::class),
            'www.example.com'
        );

        $this->assertInstanceOf(ContainsRecordedEventsInterface::class, $host);
        $this->assertSame($identity, $host->identity());
        $this->assertSame('www.example.com', $host->name());
        $this->assertSame('www.example.com', (string) $host);
        $this->assertCount(0, $host->recordedEvents());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyName()
    {
        new Host(
            $this->createMock(IdentityInterface::class),
            ''
        );
    }

    public function testRegister()
    {
        $host = Host::register(
            $identity = $this->createMock(IdentityInterface::class),
            'www.example.com'
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
            'www.example.com',
            $host->recordedEvents()->current()->name()
        );
    }
}
