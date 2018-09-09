<?php
declare(strict_types = 1);

namespace Tests\Web;

use function Web\bootstrap;
use Domain\Repository\{
    HttpResourceRepository,
    ImageRepository,
    HtmlPageRepository,
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\DBAL\Connection;
use Innmind\HttpFramework\RequestHandler;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $handler = bootstrap(
            $this->createMock(CommandBusInterface::class),
            $this->createMock(Connection::class),
            $this->createMock(HttpResourceRepository::class),
            $this->createMock(ImageRepository::class),
            $this->createMock(HtmlPageRepository::class)
        );

        $this->assertInstanceOf(RequestHandler::class, $handler);
    }
}
