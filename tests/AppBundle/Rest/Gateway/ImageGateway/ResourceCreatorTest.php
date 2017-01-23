<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\ImageGateway;

use AppBundle\Rest\Gateway\ImageGateway\ResourceCreator;
use Domain\Command\{
    RegisterImage,
    RegisterDomain,
    RegisterHost,
    Image\SpecifyDimension,
    Image\SpecifyWeight,
    Image\AddDescription
};
use Innmind\Rest\Server\{
    ResourceCreatorInterface,
    Definition\HttpResource as Definition,
    Definition\Identity,
    Definition\Gateway,
    Definition\Property as PropertyDefinition,
    HttpResourceInterface,
    Property
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Immutable\{
    Map,
    Set
};

class ResourceCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ResourceCreatorInterface::class,
            new ResourceCreator(
                $this->createMock(CommandBusInterface::class)
            )
        );
    }

    public function testExcecution()
    {
        $expected = null;
        $creator = new ResourceCreator(
            $bus = $this->createMock(CommandBusInterface::class)
        );
        // $bus
        //     ->expects($this->at(0))
        //     ->method('handle')
        //     ->with($this->callback(function($command) {
        //         return $command instanceof RegisterDomain &&
        //             (string) $command->host() === 'example.com';
        //     }));
        // $bus
        //     ->expects($this->at(1))
        //     ->method('handle')
        //     ->with($this->callback(function($command) {
        //         return $command instanceof RegisterHost &&
        //             (string) $command->host() === 'example.com';
        //     }));
        // $bus
        //     ->expects($this->at(2))
        //     ->method('handle')
        //     ->with($this->callback(function($command) use (&$expected): bool {
        //         $expected = $command->identity();

        //         return $command instanceof RegisterImage &&
        //             (string) $command->path() === 'foo' &&
        //             (string) $command->query() === 'bar';
        //     }));
        // $bus
        //     ->expects($this->at(3))
        //     ->method('handle')
        //     ->with($this->callback(function($command) {
        //         return $command instanceof SpecifyDimension &&
        //             (string) $command->dimension() === '42x24';
        //     }));
        // $bus
        //     ->expects($this->at(4))
        //     ->method('handle')
        //     ->with($this->callback(function($command) {
        //         return $command instanceof SpecifyWeight &&
        //             $command->weight()->toInt() === 1337;
        //     }));
        // $bus
        //     ->expects($this->at(5))
        //     ->method('handle')
        //     ->with($this->callback(function($command) {
        //         return $command instanceof AddDescription &&
        //             (string) $command->description() === 'whatever';
        //     }));
        $definition = new Definition(
            'image',
            new Identity('identity'),
            new Map('string', PropertyDefinition::class),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('image'),
            false,
            new Map('string', 'string')
        );
        $resource = $this->createMock(HttpResourceInterface::class);
        $resource
            ->expects($this->at(0))
            ->method('property')
            ->with('host')
            ->willReturn(new Property('host', 'example.com'));
        $resource
            ->expects($this->at(1))
            ->method('property')
            ->with('path')
            ->willReturn(new Property('path', 'foo'));
        $resource
            ->expects($this->at(2))
            ->method('property')
            ->with('query')
            ->willReturn(new Property('query', 'bar'));
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

        // $this->assertSame($expected, $identity);
    }
}
