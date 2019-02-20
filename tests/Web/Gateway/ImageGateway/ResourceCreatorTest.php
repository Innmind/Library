<?php
declare(strict_types = 1);

namespace Tests\Web\Gateway\ImageGateway;

use Web\Gateway\ImageGateway\ResourceCreator;
use Domain\Command\{
    RegisterImage,
    RegisterDomain,
    RegisterHost,
    Image\SpecifyDimension,
    Image\SpecifyWeight,
    Image\AddDescription,
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

                return $command instanceof RegisterImage &&
                    (string) $command->path() === 'foo' &&
                    (string) $command->query() === 'bar';
            }));
        $bus
            ->expects($this->at(3))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyDimension &&
                    (string) $command->dimension() === '42x24';
            }));
        $bus
            ->expects($this->at(4))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof SpecifyWeight &&
                    $command->weight()->toInt() === 1337;
            }));
        $bus
            ->expects($this->at(5))
            ->method('__invoke')
            ->with($this->callback(function($command) {
                return $command instanceof AddDescription &&
                    (string) $command->description() === 'foo';
            }));
        $definition = new Definition(
            'image',
            new Gateway('image'),
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
            ->with('dimension')
            ->willReturn(true);
        $resource
            ->expects($this->at(4))
            ->method('property')
            ->with('dimension')
            ->willReturn(
                new Property(
                    'dimension',
                    (new Map('string', 'int'))
                        ->put('width', 42)
                        ->put('height', 24)
                )
            );
        $resource
            ->expects($this->at(5))
            ->method('has')
            ->with('weight')
            ->willReturn(true);
        $resource
            ->expects($this->at(6))
            ->method('property')
            ->with('weight')
            ->willReturn(new Property('weight', 1337));
        $resource
            ->expects($this->at(7))
            ->method('has')
            ->with('descriptions')
            ->willReturn(true);
        $resource
            ->expects($this->at(8))
            ->method('property')
            ->with('descriptions')
            ->willReturn(
                new Property(
                    'descriptions',
                    (new Set('string'))->add('foo')
                )
            );

        $identity = $creator($definition, $resource);

        $this->assertSame($expected, $identity);
    }
}
