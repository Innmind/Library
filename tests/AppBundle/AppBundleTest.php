<?php
declare(strict_types = 1);

namespace Tests\AppBundle;

use AppBundle\{
    AppBundle,
    DependencyInjection\Compiler\ConfigureONMDataExtractorPass
};
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container
            ->expects($this->once())
            ->method('addCompilerPass')
            ->with($this->callback(function($pass) {
                return $pass instanceof ConfigureONMDataExtractorPass;
            }));
        $bundle = new AppBundle;

        $this->assertNull($bundle->build($container));
    }
}
