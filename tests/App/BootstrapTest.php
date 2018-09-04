<?php
declare(strict_types = 1);

namespace Tests\App;

use function App\bootstrap;
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
    }
}
