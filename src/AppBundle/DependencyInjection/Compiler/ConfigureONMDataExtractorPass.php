<?php
declare(strict_types = 1);

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\{
    ContainerBuilder,
    Reference,
    Compiler\CompilerPassInterface
};

final class ConfigureONMDataExtractorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container
            ->getDefinition('innmind_neo4j.entity.data_extractor.aggregate')
            ->replaceArgument(
                0,
                new Reference('onm.data_extractor.strategy')
            );
        $container
            ->getDefinition('innmind_neo4j.entity.data_extractor.relationship')
            ->replaceArgument(
                0,
                new Reference('onm.data_extractor.strategy')
            );
    }
}
