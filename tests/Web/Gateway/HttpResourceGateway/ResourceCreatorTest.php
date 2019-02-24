<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\HttpResourceGateway;

use Web\Gateway\HttpResourceGateway\ResourceCreator;
use Domain\Command\{
    RegisterHttpResource,
    RegisterDomain,
    RegisterHost,
    HttpResource\SpecifyCharset,
    HttpResource\SpecifyLanguages,
};
use Innmind\Rest\Server\{
    ResourceCreator as ResourceCreatorInterface,
    Definition\HttpResource as Definition,
    Definition\Identity,
    Definition\Gateway,
    Definition\Property as PropertyDefinition,
    HttpResource,
    HttpResource\Property,
};
use Innmind\CommandBus\CommandBus;
use Innmind\Immutable\{
    Map,
    Set,
};
use PHPUnit\Framework\TestCase;

class ResourceCreatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ResourceCreatorInterface::class,
            new ResourceCreator(
                $this->createMock(CommandBus::class)
            )
        );
    }

    public function testExcecution()
    {
        $expected = null;
        $creator = new ResourceCreator(
            $bus = $this->createMock(CommandBus::class)
        );
        $bus
            ->expects($this->at(0))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterDomain &&
                    (string) $command->host() === 'example.com';
            }));
        $bus
            ->expects($this->at(1))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof RegisterHost &&
                    (string) $command->host() === 'example.com';
            }));
        $bus
            ->expects($this->at(2))
            ->method('__invoke')
            ->with($this->callback(function($command) use (&$expected): bool {
                $expected = $command->identity();

                return $command instanceof RegisterHttpResource &&
                    (string) $command->path() === 'foo' &&
                    (string) $command->query() === 'bar';
            }));
        $bus
            ->expects($this->at(3))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyCharset &&
                    (string) $command->charset() === 'UTF-8';
            }));
        $bus
            ->expects($this->at(4))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyLanguages &&
                    $command->languages()->size() === 1 &&
                    (string) $command->languages()->current() === 'fr';
            }));
        $definition = new Definition(
            'http_resource',
            new Gateway('http_resource'),
            new Identity('identity'),
            Set::of(PropertyDefinition::class)
        );
        $resource = $this->createMock(HttpResource::class);
        $resource
            ->expects($this->at(0))
            ->method('property')
            ->with('host')
            ->willReturn(new Property('host', 'example.com'));
        $resource
            ->expects($this->at(1))
            ->method('property')
            ->with('query')
            ->willReturn(new Property('query', 'bar'));
        $resource
            ->expects($this->at(2))
            ->method('property')
            ->with('path')
            ->willReturn(new Property('path', 'foo'));
        $resource
            ->expects($this->at(3))
            ->method('has')
            ->with('charset')
            ->willReturn(true);
        $resource
            ->expects($this->at(4))
            ->method('property')
            ->with('charset')
            ->willReturn(new Property('charset', 'UTF-8'));
        $resource
            ->expects($this->at(5))
            ->method('has')
            ->with('languages')
            ->willReturn(true);
        $resource
            ->expects($this->at(6))
            ->method('property')
            ->with('languages')
            ->willReturn(new Property('languages', (new Set('string'))->add('fr')));

        $identity = $creator($definition, $resource);

        $this->assertSame($expected, $identity);
    }
}
