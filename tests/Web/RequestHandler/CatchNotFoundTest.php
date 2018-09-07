<?php
declare(strict_types = 1);

namespace Tests\Web\RequestHandler;

use Web\RequestHandler\CatchNotFound;
use Domain\Exception\{
    AlternateNotFound,
    AuthorNotFound,
    CanonicalNotFound,
    CitationNotFound,
    CitationAppearanceNotFound,
    DomainNotFound,
    HostNotFound,
    HtmlPageNotFound,
    HttpResourceNotFound,
    ImageNotFound,
    ReferenceNotFound,
};
use Innmind\HttpFramework\RequestHandler;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use PHPUnit\Framework\TestCase;

class CatchNotFoundTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            new CatchNotFound($this->createMock(RequestHandler::class))
        );
    }

    public function testHandle()
    {
        $handle = new CatchNotFound(
            $inner = $this->createMock(RequestHandler::class)
        );
        $request = $this->createMock(ServerRequest::class);
        $inner
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn($expected = $this->createMock(Response::class));

        $this->assertSame($expected, $handle($request));
    }

    /**
     * @dataProvider exceptions
     */
    public function testCatch($exception)
    {
        $handle = new CatchNotFound(
            $inner = $this->createMock(RequestHandler::class)
        );
        $request = $this->createMock(ServerRequest::class);
        $inner
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->will($this->throwException($exception));

        $response = $handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(404, $response->statusCode()->value());
    }

    public function exceptions(): array
    {
        return [
            [new AlternateNotFound],
            [new AuthorNotFound],
            [new CanonicalNotFound],
            [new CitationNotFound],
            [new CitationAppearanceNotFound],
            [new DomainNotFound],
            [new HostNotFound],
            [new HtmlPageNotFound],
            [new HttpResourceNotFound],
            [new ImageNotFound],
            [new ReferenceNotFound],
        ];
    }
}
