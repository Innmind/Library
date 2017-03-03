<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway;

use AppBundle\Rest\Gateway\{
    HtmlPageGateway,
    HtmlPageGateway\ResourceCreator,
    HtmlPageGateway\ResourceAccessor,
    HtmlPageGateway\ResourceLinker
};
use Domain\Repository\HtmlPageRepositoryInterface;
use Innmind\Rest\Server\GatewayInterface;
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\DBAL\ConnectionInterface;
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
                $this->createMock(HtmlPageRepositoryInterface::class),
                $this->createMock(ConnectionInterface::class)
            ),
            $this->linker = new ResourceLinker(
                $this->createMock(CommandBusInterface::class)
            )
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            GatewayInterface::class,
            $this->gateway
        );
    }

    public function testResourceCreator()
    {
        $this->assertSame($this->creator, $this->gateway->resourceCreator());
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplementedException
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
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplementedException
     */
    public function testThrowWhenAccessingResourceUpdater()
    {
        $this->gateway->resourceUpdater();
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplementedException
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
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplementedException
     */
    public function testThrowWhenAccessingResourceUnlinker()
    {
        $this->gateway->resourceUnlinker();
    }
}
