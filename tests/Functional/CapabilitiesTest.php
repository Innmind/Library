<?php
declare(strict_types = 1);

namespace Tests\Functional;

use function Web\bootstrap as web;
use Domain\Repository\{
    HttpResourceRepository,
    ImageRepository,
    HtmlPageRepository,
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Neo4j\DBAL\Connection;
use Innmind\Http\{
    Message\ServerRequest\ServerRequest,
    Message\Response,
    Message\Method\Method,
    ProtocolVersion\ProtocolVersion,
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class CapabilitiesTest extends TestCase
{
    public function testLoad()
    {
        $handle = web(
            $this->createMock(CommandBusInterface::class),
            $this->createMock(Connection::class),
            $this->createMock(HttpResourceRepository::class),
            $this->createMock(ImageRepository::class),
            $this->createMock(HtmlPageRepository::class)
        );

        $response = $handle(new ServerRequest(
            Url::fromString('http://localhost/*'),
            Method::options(),
            new ProtocolVersion(1, 1)
        ));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->statusCode()->value());
        $this->assertSame(
            'Link: </api/web/resource/>; rel="web.resource", </api/web/image/>; rel="web.image", </api/web/html_page/>; rel="web.html_page"',
            (string) $response->headers()->get('link')
        );
        $this->assertSame('', (string) $response->body());
    }
}
