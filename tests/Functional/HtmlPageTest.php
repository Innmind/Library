<?php
declare(strict_types = 1);

namespace Tests\Functional;

use function Web\bootstrap as web;
use Domain\{
    Repository\HttpResourceRepository,
    Repository\ImageRepository,
    Repository\HtmlPageRepository,
    Exception\HtmlPageNotFound,
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

class HtmlPageTest extends TestCase
{
    public function testNotFound()
    {
        $handle = web(
            $this->createMock(CommandBusInterface::class),
            $this->createMock(Connection::class),
            $this->createMock(HttpResourceRepository::class),
            $this->createMock(ImageRepository::class),
            $repository = $this->createMock(HtmlPageRepository::class),
            'api_key'
        );
        $repository
            ->expects($this->once())
            ->method('get')
            ->will($this->throwException(new HtmlPageNotFound));

        $response = $handle(new ServerRequest(
            Url::fromString('http://localhost/api/web/html_page/fc1cc2b4-2c09-43b1-8c19-3499068950ed'),
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
            Url::fromString('http://localhost/api/web/html_page/fc1cc2b4-2c09-43b1-8c19-3499068950ed'),
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
            Url::fromString('http://localhost/api/web/html_page/'),
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
            '{"identity":"identity","properties":{"identity":{"type":"string","access":["READ"],"variants":[],"optional":false},"host":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":false},"author":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"citations":{"type":"set<string>","access":["READ","CREATE"],"variants":[],"optional":true},"path":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":false},"query":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":false},"languages":{"type":"set<string>","access":["READ","CREATE"],"variants":[],"optional":true},"charset":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"main_content":{"type":"string","access":["CREATE"],"variants":[],"optional":true},"description":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"anchors":{"type":"set<string>","access":["READ","CREATE"],"variants":[],"optional":true},"theme_colour":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"title":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"android_app_link":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"ios_app_link":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"preview":{"type":"string","access":["READ","CREATE"],"variants":[],"optional":true},"is_journal":{"type":"bool","access":["CREATE"],"variants":[],"optional":true}},"metas":{"allowed_media_types":["text\/html","text\/xml","application\/xml","application\/xhtml+xml"]},"rangeable":true,"linkable_to":{"alternate":"web.html_page","canonical":"web.html_page"}}',
            (string) $response->body()
        );
    }
}
