<?php
declare(strict_types = 1);

namespace Tests\Domain\Event;

use Domain\{
    Event\HostResourceCreated,
    Entity\HostResource\IdentityInterface,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

class HostResourceCreatedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new HostResourceCreated(
            $identity = $this->createMock(IdentityInterface::class),
            $host = $this->createMock(HostIdentity::class),
            $resource = $this->createMock(ResourceIdentity::class),
            $foundAt = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($host, $event->host());
        $this->assertSame($resource, $event->resource());
        $this->assertSame($foundAt, $event->foundAt());
    }
}
