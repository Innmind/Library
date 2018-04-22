<?php
declare(strict_types = 1);

namespace Tests\AppBundle;

use AppBundle\Rest\Gateway\{
    HttpResourceGateway,
    ImageGateway,
    HtmlPageGateway,
};
use Innmind\Compose\ContainerBuilder\ContainerBuilder;
use Innmind\Url\Path;
use Innmind\Immutable\Map;
use Symfony\Component\Yaml\Yaml;
use Psr\Log\NullLogger;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testInterface()
    {
        $entities = [];

        foreach (glob('src/AppBundle/Resources/config/neo4j/*.yml') as $file) {
            $entities[] = Yaml::parseFile($file);
        }

        $container = (new ContainerBuilder)(
            new Path('config/container.yml'),
            (new Map('string', 'mixed'))
                ->put('entities', $entities)
                ->put('logger', new NullLogger)
                ->put('domainEventsStorage', '/tmp')
        );

        $this->assertTrue($container->has('httpResource'));
        $this->assertTrue($container->has('image'));
        $this->assertTrue($container->has('htmlPage'));
        $this->assertInstanceOf(HttpResourceGateway::class, $container->get('httpResource'));
        $this->assertInstanceOf(ImageGateway::class, $container->get('image'));
        $this->assertInstanceOf(HtmlPageGateway::class, $container->get('htmlPage'));
    }
}
