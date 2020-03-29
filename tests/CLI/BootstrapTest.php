<?php
declare(strict_types = 1);

namespace Tests\CLI;

use function CLI\bootstrap;
use Innmind\CLI\Commands;
use Innmind\OperatingSystem\OperatingSystem;
use Innmind\Server\Status\Server;
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $os = $this->createMock(OperatingSystem::class);
        $os
            ->expects($this->any())
            ->method('status')
            ->willReturn($status = $this->createMock(Server::class));
        $status
            ->expects($this->any())
            ->method('tmp')
            ->willReturn(Path::none());

        $this->assertInstanceOf(
            Commands::class,
            bootstrap($os)
        );
    }
}
