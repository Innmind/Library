<?php
declare(strict_types = 1);

namespace Tests\Web\RequestHandler;

use Web\RequestHandler\Debug;
use Innmind\HttpFramework\RequestHandler;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use PHPUnit\Framework\TestCase;

class DebugTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            new Debug(
                $this->createMock(RequestHandler::class),
                true
            )
        );
    }

    public function testDoesntCatchExceptionWhenNotDebugging()
    {
        $debug = new Debug(
            $handler = $this->createMock(RequestHandler::class),
            false
        );
        $request = $this->createMock(ServerRequest::class);
        $handler
            ->expects($this->at(0))
            ->method('__invoke')
            ->with($request)
            ->willReturn($expected = $this->createMock(Response::class));
        $handler
            ->expects($this->at(1))
            ->method('__invoke')
            ->with($request)
            ->will($this->throwException(new \Exception));

        $this->assertSame($expected, $debug($request));

        $this->expectException(\Exception::class);

        $debug($request);
    }

    public function testDoesntTamperWithReturnedResponseWhileDebugging()
    {
        $debug = new Debug(
            $handler = $this->createMock(RequestHandler::class),
            true
        );
        $request = $this->createMock(ServerRequest::class);
        $handler
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn($expected = $this->createMock(Response::class));

        $this->assertSame($expected, $debug($request));
    }

    public function testReturnResponseWhenExceptionThrownWhileDebugging()
    {
        $debug = new Debug(
            $handler = $this->createMock(RequestHandler::class),
            true
        );
        $request = $this->createMock(ServerRequest::class);
        $handler
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->will($this->throwException(new \Exception));

        $response = $debug($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(500, $response->statusCode()->value());
    }
}
