<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway;

use Web\Gateway\{
    ImageGateway,
    ImageGateway\ResourceCreator,
    ImageGateway\ResourceAccessor,
};
use Domain\Repository\ImageRepository;
use Innmind\Rest\Server\{
    Gateway,
    Exception\ActionNotImplemented,
};
use Innmind\CommandBus\CommandBus;
use Innmind\Neo4j\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class ImageGatewayTest extends TestCase
{
    private $gateway;
    private $creator;
    private $accessor;

    public function setUp(): void
    {
        $this->gateway = new ImageGateway(
            $this->creator = new ResourceCreator(
                $this->createMock(CommandBus::class)
            ),
            $this->accessor = new ResourceAccessor(
                $this->createMock(ImageRepository::class),
                $this->createMock(Connection::class)
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

    public function testThrowWhenAccessingResourceLinker()
    {
        $this->expectException(ActionNotImplemented::class);

        $this->gateway->resourceLinker();
    }

    public function testThrowWhenAccessingResourceUnlinker()
    {
        $this->expectException(ActionNotImplemented::class);

        $this->gateway->resourceUnlinker();
    }
}
