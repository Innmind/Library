<?php
declare(strict_types = 1);

namespace Tests\AppBundle\DependencyInjection\Compiler;

use AppBundle\DependencyInjection\Compiler\ConfigureONMDataExtractorPass;
use Symfony\Component\DependencyInjection\{
    ContainerBuilder,
    Reference,
    Definition,
    Compiler\CompilerPassInterface
};

class ConfigureONMDataExtractorPassTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CompilerPassInterface::class,
            new ConfigureONMDataExtractorPass
        );
    }

    public function testProcess()
    {
        $container = new ContainerBuilder;
        $container->setDefinition(
            'innmind_neo4j.entity.data_extractor.aggregate',
            $aggregate = new Definition(
                'foo',
                [null]
            )
        );
        $container->setDefinition(
            'innmind_neo4j.entity.data_extractor.relationship',
            $relationship = new Definition(
                'foo',
                [null]
            )
        );

        $this->assertNull((new ConfigureONMDataExtractorPass)->process($container));
        $this->assertInstanceOf(
            Reference::class,
            $aggregate->getArgument(0)
        );
        $this->assertInstanceOf(
            Reference::class,
            $relationship->getArgument(0)
        );
        $this->assertSame(
            'onm.data_extractor.strategies',
            (string) $aggregate->getArgument(0)
        );
        $this->assertSame(
            'onm.data_extractor.strategies',
            (string) $relationship->getArgument(0)
        );
    }
}
