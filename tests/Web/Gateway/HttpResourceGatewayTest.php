<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway;

use Web\Gateway\{
    HttpResourceGateway,
    HttpResourceGateway\ResourceCreator,
    HttpResourceGateway\ResourceAccessor,
    HttpResourceGateway\ResourceLinker,
};
use Domain\Repository\HttpResourceRepository;
use Innmind\Rest\Server\{
    Gateway,
    Exception\ActionNotImplemented,
};
use Innmind\CommandBus\CommandBus;
use Innmind\Neo4j\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class HttpResourceGatewayTest extends TestCase
{
    private $gateway;
    private $creator;
    private $accessor;
    private $linker;

    public function setUp(): void
    {
        $this->gateway = new HttpResourceGateway(
            $this->creator = new ResourceCreator(
                $this->createMock(CommandBus::class)
            ),
            $this->accessor = new ResourceAccessor(
                $this->createMock(HttpResourceRepository::class),
                $this->createMock(Connection::class)
            ),
            $this->linker = new ResourceLinker(
                $this->createMock(CommandBus::class)
            )
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            Gateway::class,
            $this->gateway
        );
    }

    public function testResourceCreator()
    {
        $this->assertSame($this->creator, $this->gateway->resourceCreator());
    }

    public function testThrowWhenAccessingResourceListAccessor()
    {
        $this->expectException(ActionNotImplemented::class);

        $this->gateway->resourceListAccessor();
    }

    public function testResourceAccessor()
    {
        $this->assertSame(
            $this->accessor,
            $this->gateway->resourceAccessor()
        );
    }

    public function testThrowWhenAccessingResourceUpdater()
    {
        $this->expectException(ActionNotImplemented::class);

        $this->gateway->resourceUpdater();
    }

    public function testThrowWhenAccessingResourceRemover()
    {
        $this->expectException(ActionNotImplemented::class);

        $this->gateway->resourceRemover();
    }

    public function testResourceLinker()
    {
        $this->assertSame(
            $this->linker,
            $this->gateway->resourceLinker()
        );
    }

    public function testThrowWhenAccessingResourceUnlinker()
    {
        $this->expectException(ActionNotImplemented::class);

        $this->gateway->resourceUnlinker();
    }
}
