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
use Innmind\Filesystem\Adapter\InMemory;
use Innmind\CommandBus\CommandBus;
use Innmind\Neo4j\DBAL\Connection;
use Innmind\HttpTransport\Transport;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $app = bootstrap(
            static function() {
                return Map::of('string', 'callable');
            },
            $this->createMock(Transport::class),
            Url::of('http://neo4j:ci@neo4j:7474/'),
            new InMemory
        );

        $this->assertInstanceOf(CommandBus::class, $app['command_bus']);
        $this->assertInstanceOf(HttpResourceRepository::class, $app['repository']['http_resource']);
        $this->assertInstanceOf(ImageRepository::class, $app['repository']['image']);
        $this->assertInstanceOf(HtmlPageRepository::class, $app['repository']['html_page']);
        $this->assertInstanceOf(Connection::class, $app['dbal']);
    }
}
