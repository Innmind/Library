<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway;

use AppBundle\Rest\Gateway\{
    AuthorGateway,
    AuthorGateway\ResourceCreator
};
use Innmind\Rest\Server\GatewayInterface;
use Innmind\CommandBus\CommandBusInterface;

class AuthorGatewayTest extends \PHPUnit_Framework_TestCase
{
    private $gateway;
    private $creator;

    public function setUp()
    {
        $this->gateway = new AuthorGateway(
            $this->creator = new ResourceCreator(
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

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplementedException
     */
    public function testThrowWhenAccessingResourceAccessor()
    {
        $this->gateway->resourceAccessor();
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

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplementedException
     */
    public function testThrowWhenAccessingResourceLinker()
    {
        $this->gateway->resourceLinker();
    }

    /**
     * @expectedException Innmind\Rest\Server\Exception\ActionNotImplementedException
     */
    public function testThrowWhenAccessingResourceUnlinker()
    {
        $this->gateway->resourceUnlinker();
    }
}
