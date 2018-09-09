<?php
declare(strict_types = 1);

namespace Tests\Web\Controller;

use Web\Controller\Capabilities;
use Innmind\HttpFramework\Controller;
use Innmind\Rest\Server\{
    Controller\Capabilities as RestCapabilities,
    Routing\Routes,
    Router,
};
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Router\Route;
use Innmind\Immutable\{
    Map,
    Str,
};
use PHPUnit\Framework\TestCase;

class CapabilitiesTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Controller::class,
            new Capabilities(
                new RestCapabilities(
                    $routes = new Routes,
                    new Router($routes)
                )
            )
        );
    }

    public function testInvokation()
    {
        $handle = new Capabilities(
            new RestCapabilities(
                $routes = new Routes,
                new Router($routes)
            )
        );

        $response = $handle(
            $this->createMock(ServerRequest::class),
            Route::of(new Route\Name('capabilities'), Str::of('OPTIONS /*')),
            new Map('string', 'string')
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
    }
}
