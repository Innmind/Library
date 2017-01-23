<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\ConfigureONMDataExtractorPass;
use Symfony\Component\{
    HttpKernel\Bundle\Bundle,
    DependencyInjection\ContainerBuilder
};

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConfigureONMDataExtractorPass);
    }
}
