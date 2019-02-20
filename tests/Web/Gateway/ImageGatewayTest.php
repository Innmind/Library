<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway;

use Web\Gateway\{
    ImageGateway,
    ImageGateway\ResourceCreator,
    ImageGateway\ResourceAccessor,
};
use Domain\Repository\ImageRepository;
use Innmind\Rest\Server\Gateway;
use Innmind\CommandBus\CommandBus;
use Innmind\Neo4j\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class ImageGatewayTest extends TestCase
{
    private $gateway;
    private $creator;
    private $accessor;

    public function setUp()
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

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplemented
     */
    public function testThrowWhenAccessingResourceListAccessor()
    {
        $this->gateway->resourceListAccessor();
    }

    public function testResourceAccessor()
    {
        $this->assertSame(
            $this->accessor,
            $this->gateway->resourceAccessor()
        );
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplemented
     */
    public function testThrowWhenAccessingResourceUpdater()
    {
        $this->gateway->resourceUpdater();
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplemented
     */
    public function testThrowWhenAccessingResourceRemover()
    {
        $this->gateway->resourceRemover();
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplemented
     */
    public function testThrowWhenAccessingResourceLinker()
    {
        $this->gateway->resourceLinker();
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplemented
     */
    public function testThrowWhenAccessingResourceUnlinker()
    {
        $this->gateway->resourceUnlinker();
    }
}
