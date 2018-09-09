<?php
declare(strict_types = 1);

namespace Tests\Web\Controller;

use Web\{
    Controller\RestBridge,
    Routes,
};
use Innmind\HttpFramework\Controller;
use Innmind\Rest\Server\{
    Controller as RestController,
    Definition\HttpResource,
    Definition\Loader\YamlLoader,
    Routing\Routes as RestRoutes,
    Identity\Identity,
};
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Router\Route;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class RestBridgeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Controller::class,
            new RestBridge(
                $this->createMock(RestController::class),
                new Map(Route::class, HttpResource::class)
            )
       );
    }

    public function testThrowWhenInvalidDefinitionMapKey()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 2 must be of type MapInterface<Innmind\Router\Route, Innmind\Rest\Server\Definition\HttpResource>');

        new RestBridge(
            $this->createMock(RestController::class),
            new Map('string', HttpResource::class)
        );
    }

    public function testThrowWhenInvalidDefinitionMapValue()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 2 must be of type MapInterface<Innmind\Router\Route, Innmind\Rest\Server\Definition\HttpResource>');

        new RestBridge(
            $this->createMock(RestController::class),
            new Map(Route::class, 'callable')
        );
    }

    public function testInvokationWithoutIdentity()
    {
        $handle = new RestBridge(
            $controller = $this->createMock(RestController::class),
            $routes = Routes::from(RestRoutes::from(
                (new YamlLoader)('src/Web/config/rest.yml')
            ))
        );
        $request = $this->createMock(ServerRequest::class);
        $controller
            ->expects($this->once())
            ->method('__invoke')
            ->with(
                $request,
                $routes->current(),
                null
            )
            ->willReturn($expected = $this->createMock(Response::class));

        $this->assertSame($expected, $handle(
            $request,
            $routes->key(),
            new Map('string', 'string')
        ));
    }

    public function testInvokationWithIdentity()
    {
        $handle = new RestBridge(
            $controller = $this->createMock(RestController::class),
            $routes = Routes::from(RestRoutes::from(
                (new YamlLoader)('src/Web/config/rest.yml')
            ))
        );
        $request = $this->createMock(ServerRequest::class);
        $controller
            ->expects($this->once())
            ->method('__invoke')
            ->with(
                $request,
                $routes->current(),
                new Identity('foobar')
            )
            ->willReturn($expected = $this->createMock(Response::class));

        $this->assertSame($expected, $handle(
            $request,
            $routes->key(),
            (new Map('string', 'string'))
                ->put('identity', 'foobar')
        ));
    }
}
