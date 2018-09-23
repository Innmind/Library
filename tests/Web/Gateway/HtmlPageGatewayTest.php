<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway;

use Web\Gateway\{
    HtmlPageGateway,
    HtmlPageGateway\ResourceCreator,
    HtmlPageGateway\ResourceAccessor,
    HtmlPageGateway\ResourceLinker
};
use Domain\Repository\HtmlPageRepository;
use Innmind\Rest\Server\Gateway;
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class HtmlPageGatewayTest extends TestCase
{
    private $gateway;
    private $creator;
    private $accessor;
    private $linker;

    public function setUp()
    {
        $this->gateway = new HtmlPageGateway(
            $this->creator = new ResourceCreator(
                $this->createMock(CommandBusInterface::class)
            ),
            $this->accessor = new ResourceAccessor(
                $this->createMock(HtmlPageRepository::class),
                $this->createMock(Connection::class)
            ),
            $this->linker = new ResourceLinker(
                $this->createMock(CommandBusInterface::class)
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

    public function testResourceLinker()
    {
        $this->assertSame(
            $this->linker,
            $this->gateway->resourceLinker()
        );
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplemented
     */
    public function testThrowWhenAccessingResourceUnlinker()
    {
        $this->gateway->resourceUnlinker();
    }
}
