<?php
declare(strict_types = 1);

namespace Tests\Functional;

use function Web\bootstrap as web;
use Domain\{
    Repository\HttpResourceRepository,
    Repository\ImageRepository,
    Repository\HtmlPageRepository,
    Exception\HttpResourceNotFound,
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\DBAL\Connection;
use Innmind\Http\{
    Message\ServerRequest\ServerRequest,
    Message\Response,
    Message\Method\Method,
    ProtocolVersion\ProtocolVersion,
    Headers\Headers,
    Header\Accept,
    Header\AcceptValue,
    Header\Authorization,
    Header\AuthorizationValue,
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class HttpResourceTest extends TestCase
{
    public function testNotFound()
    {
        $handle = web(
            $this->createMock(CommandBusInterface::class),
            $this->createMock(Connection::class),
            $repository = $this->createMock(HttpResourceRepository::class),
            $this->createMock(ImageRepository::class),
            $this->createMock(HtmlPageRepository::class),
            'api_key'
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new HttpResourceNotFound));

        $response = $handle(new ServerRequest(
            Url::fromString('http://localhost/api/web/resource/fc1cc2b4-2c09-43b1-8c19-3499068950ed'),
            Method::get(),
            new ProtocolVersion(1, 1),
            Headers::of(
                new Accept(
                    new AcceptValue('application', 'json')
                ),
                new Authorization(
                    new AuthorizationValue('Bearer', 'api_key')
                )
            )
        ));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(404, $response->statusCode()->value());
        $this->assertSame('', (string) $response->body());
    }

    public function testErrorWhenNoAuth()
    {
        $handle = web(
            $this->createMock(CommandBusInterface::class),
            $this->createMock(Connection::class),
            $this->createMock(HttpResourceRepository::class),
            $this->createMock(ImageRepository::class),
            $this->createMock(HtmlPageRepository::class),
            'api_key'
        );

        $response = $handle(new ServerRequest(
            Url::fromString('http://localhost/api/web/resource/fc1cc2b4-2c09-43b1-8c19-3499068950ed'),
            Method::get(),
            new ProtocolVersion(1, 1),
            Headers::of(
                new Accept(
                    new AcceptValue('application', 'json')
                )
            )
        ));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(401, $response->statusCode()->value());
        $this->assertSame('', (string) $response->body());
    }

    public function testOptions()
    {
        $handle = web(
            $this->createMock(CommandBusInterface::class),
            $this->createMock(Connection::class),
            $this->createMock(HttpResourceRepository::class),
            $this->createMock(ImageRepository::class),
            $this->createMock(HtmlPageRepository::class),
            'api_key'
        );

        $response = $handle(new ServerRequest(
            Url::fromString('http://localhost/api/web/resource/'),
            Method::options(),
            new ProtocolVersion(1, 1),
            Headers::of(
                new Accept(
                    new AcceptValue('application', 'json')
                ),
                new Authorization(
                    new AuthorizationValue('Bearer', 'api_key')
                )
            )
        ));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
        $this->assertSame(
            'Content-Type: application/json',
            (string) $response->headers()->get('content-type')
        );
        $this->assertSame(
            '{"identity":"identity","properties":{"identity":{"type":"string","access":["READ"],"variants":[],"optional":false},"host":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":false},"path":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":false},"query":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":false},"languages":{"type":"set<string>","access":["READ","CREATE"],"variants":[],"optional":true},"charset":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true}},"metas":{"allowed_media_types":["*\/*; q=0.1"]},"rangeable":true,"linkable_to":{"referrer":"web.resource"}}',
            (string) $response->body()
        );
    }
}
