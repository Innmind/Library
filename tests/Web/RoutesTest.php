<?php
declare(strict_types = 1);

namespace Tests\Web;

use Web\Routes;
use Innmind\Rest\Server\{
    Routing\Routes as RestRoutes,
    Definition\Loader\YamlLoader,
    Definition\HttpResource,
};
use Innmind\Router\Route;
use Innmind\Immutable\MapInterface;
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    public function testFrom()
    {
        $routes = Routes::from(RestRoutes::from(
            (new YamlLoader)('src/Web/config/rest.yml')
        ));

        $this->assertInstanceOf(MapInterface::class, $routes);
        $this->assertSame(Route::class, (string) $routes->keyType());
        $this->assertSame(HttpResource::class, (string) $routes->valueType());
        $this->assertCount(11, $routes);
        $this->assertCount(3, $routes->values()->distinct());
    }
}
