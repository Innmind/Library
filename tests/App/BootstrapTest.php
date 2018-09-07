<?php
declare(strict_types = 1);

namespace Tests\App;

use function App\bootstrap;
use Domain\Repository\{
    HttpResourceRepository,
    ImageRepository,
    HtmlPageRepository,
};
use Innmind\Url\Url;
use Innmind\Filesystem\Adapter\MemoryAdapter;
use Innmind\CommandBus\CommandBusInterface;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $app = bootstrap(
            Url::fromString('http://neo4j:ci@neo4j:7474/'),
            new MemoryAdapter
        );

        $this->assertInstanceOf(CommandBusInterface::class, $app['command_bus']);
        $this->assertInstanceOf(HttpResourceRepository::class, $app['repository']['http_resource']);
        $this->assertInstanceOf(ImageRepository::class, $app['repository']['image']);
        $this->assertInstanceOf(HtmlPageRepository::class, $app['repository']['html_page']);
    }
}
