<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Rest\Gateway\DomainGateway;

use AppBundle\Rest\Gateway\DomainGateway\ResourceCreator;
use Domain\Command\RegisterDomain;
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
use Innmind\Immutable\Map;

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
        $bus
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function($command) use (&$expected): bool {
                $expected = $command->identity();

                return $command instanceof RegisterDomain &&
                    (string) $command->host() === 'foo';
            }));
        $definition = new Definition(
            'domain',
            new Identity('identity'),
            new Map('string', PropertyDefinition::class),
            new Map('scalar', 'variable'),
            new Map('scalar', 'variable'),
            new Gateway('domain'),
            false,
            new Map('string', 'string')
        );
        $resource = $this->createMock(HttpResourceInterface::class);
        $resource
            ->expects($this->once())
            ->method('property')
            ->with('authority')
            ->willReturn(new Property('authority', 'foo'));

        $identity = $creator($definition, $resource);

        $this->assertSame($expected, $identity);
    }
}
