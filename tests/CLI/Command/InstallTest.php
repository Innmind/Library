<?php
declare(strict_types = 1);

namespace Tests\CLI\Command;

use CLI\Command\Install;
use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\InstallationMonitor\{
    Client,
    Event,
};
use Innmind\Url\Path;
use Innmind\Stream\Writable;
use Innmind\Immutable\{
    Stream,
    Map,
    Str,
};
use PHPUnit\Framework\TestCase;

class InstallTest extends TestCase
{
    public function setUp()
    {
        @mkdir('/tmp/config');
    }

    public function tearDown()
    {
        @unlink('/tmp/config/.env');
        @rmdir('/tmp/config');
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            Command::class,
            new Install(
                $this->createMock(Client::class)
            )
        );
    }

    public function testUsage()
    {
        $usage = <<<USAGE
install

This will configure the config/.env file

It will do so by reading events recorded by the installation monitor
USAGE;

        $this->assertSame(
            $usage,
            (string) new Install($this->createMock(Client::class))
        );
    }

    public function testInvokation()
    {
        $install = new Install(
            $client = $this->createMock(Client::class)
        );
        $client
            ->expects($this->once())
            ->method('events')
            ->willReturn(Stream::of(
                Event::class,
                new Event(
                    new Event\Name('neo4j.password_changed'),
                    (new Map('string', 'variable'))
                        ->put('user', 'foo')
                        ->put('password', 'bar')
                )
            ));
        $client
            ->expects($this->once())
            ->method('send')
            ->with(
                new Event(
                    new Event\Name('website_available'),
                    (new Map('string', 'variable'))
                        ->put('path', '/tmp/public')
                ),
                $this->callback(static function(Event $event): bool {
                    return (string) $event->name() === 'library_installed' &&
                        $event->payload()->contains('apiKey') &&
                        strlen($event->payload()->get('apiKey')) === 40;
                })
            );
        $env = $this->createMock(Environment::class);
        $env
            ->expects($this->any())
            ->method('workingDirectory')
            ->willReturn(new Path('/tmp'));
        $env
            ->expects($this->never())
            ->method('error');

        $this->assertNull($install(
            $env,
            new Arguments,
            new Options
        ));
        $this->assertRegExp(
            "~^API_KEY=\S{40}\nNEO4J=http://foo:bar@localhost:7474/$~",
            file_get_contents('/tmp/config/.env')
        );
    }

    public function testInstallStoppedIfConfigAlreadyDone()
    {
        file_put_contents('/tmp/config/.env', 'clean');

        $install = new Install(
            $client = $this->createMock(Client::class)
        );
        $client
            ->expects($this->never())
            ->method('events');
        $client
            ->expects($this->never())
            ->method('send');
        $env = $this->createMock(Environment::class);
        $env
            ->expects($this->any())
            ->method('workingDirectory')
            ->willReturn(new Path('/tmp'));
        $env
            ->expects($this->once())
            ->method('error')
            ->willReturn($error = $this->createMock(Writable::class));
        $error
            ->expects($this->once())
            ->method('write')
            ->with(Str::of("App already installed\n"));
        $env
            ->expects($this->once())
            ->method('exit')
            ->with(1);

        $this->assertNull($install(
            $env,
            new Arguments,
            new Options
        ));
        $this->assertSame('clean', file_get_contents('/tmp/config/.env'));
    }

    public function testInstallStoppedIfNoNeo4jUserFound()
    {
        $install = new Install(
            $client = $this->createMock(Client::class)
        );
        $client
            ->expects($this->once())
            ->method('events')
            ->willReturn(Stream::of(Event::class));
        $client
            ->expects($this->never())
            ->method('send');
        $env = $this->createMock(Environment::class);
        $env
            ->expects($this->any())
            ->method('workingDirectory')
            ->willReturn(new Path('/tmp'));
        $env
            ->expects($this->once())
            ->method('error')
            ->willReturn($error = $this->createMock(Writable::class));
        $error
            ->expects($this->once())
            ->method('write')
            ->with(Str::of("Neo4j password can't be determined\n"));
        $env
            ->expects($this->once())
            ->method('exit')
            ->with(1);

        $this->assertNull($install(
            $env,
            new Arguments,
            new Options
        ));
        $this->assertFalse(file_exists('/tmp/config/.env'));
    }

    public function testInstallStoppedIfTooManyNeo4jUserFound()
    {
        $install = new Install(
            $client = $this->createMock(Client::class)
        );
        $client
            ->expects($this->once())
            ->method('events')
            ->willReturn(Stream::of(
                Event::class,
                new Event(
                    new Event\Name('neo4j.password_changed'),
                    (new Map('string', 'variable'))
                        ->put('user', 'foo')
                        ->put('password', 'bar')
                ),
                new Event(
                    new Event\Name('neo4j.password_changed'),
                    (new Map('string', 'variable'))
                        ->put('user', 'bar')
                        ->put('password', 'bar')
                )
            ));
        $client
            ->expects($this->never())
            ->method('send');
        $env = $this->createMock(Environment::class);
        $env
            ->expects($this->any())
            ->method('workingDirectory')
            ->willReturn(new Path('/tmp'));
        $env
            ->expects($this->once())
            ->method('error')
            ->willReturn($error = $this->createMock(Writable::class));
        $error
            ->expects($this->once())
            ->method('write')
            ->with(Str::of("Neo4j password can't be determined\n"));
        $env
            ->expects($this->once())
            ->method('exit')
            ->with(1);

        $this->assertNull($install(
            $env,
            new Arguments,
            new Options
        ));
        $this->assertFalse(file_exists('/tmp/config/.env'));
    }
}
