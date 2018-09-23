<?php
declare(strict_types = 1);

namespace Tests\Web\RequestHandler;

use Web\RequestHandler\CatchConflicts;
use Domain\{
    Entity\Alternate,
    Entity\HttpResource,
    Entity\Author,
    Entity\Canonical,
    Entity\Citation,
    Entity\CitationAppearance,
    Entity\Domain,
    Entity\Host,
    Entity\Reference,
    Model\Language,
    Exception\AlternateAlreadyExist,
    Exception\AuthorAlreadyExist,
    Exception\CanonicalAlreadyExist,
    Exception\CitationAlreadyExist,
    Exception\CitationAppearanceAlreadyExist,
    Exception\DomainAlreadyExist,
    Exception\HostAlreadyExist,
    Exception\HtmlPageAlreadyExist,
    Exception\HttpResourceAlreadyExist,
    Exception\ImageAlreadyExist,
    Exception\ReferenceAlreadyExist,
};
use Innmind\HttpFramework\RequestHandler;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
};
use Innmind\Url\{
    PathInterface,
    QueryInterface,
};
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class CatchConflictsTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            new CatchConflicts($this->createMock(RequestHandler::class))
        );
    }

    public function testHandle()
    {
        $handle = new CatchConflicts(
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
        $handle = new CatchConflicts(
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
        $this->assertSame(409, $response->statusCode()->value());
    }

    public function testDoesntCatch()
    {
        $handle = new CatchConflicts(
            $inner = $this->createMock(RequestHandler::class)
        );
        $request = $this->createMock(ServerRequest::class);
        $inner
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->will($this->throwException(new \Exception));

        $this->expectException(\Exception::class);

        $handle($request);
    }

    public function exceptions(): array
    {
        return [
            [
                new AlternateAlreadyExist(
                    new Alternate(
                        $this->createMock(Alternate\Identity::class),
                        $this->createMock(HttpResource\Identity::class),
                        $this->createMock(HttpResource\Identity::class),
                        new Language('fr-FR')
                    )
                ),
            ],
            [
                new AuthorAlreadyExist(
                    new Author(
                        $this->createMock(Author\Identity::class),
                        new Author\Name('foo')
                    )
                ),
            ],
            [
                new CanonicalAlreadyExist(
                    new Canonical(
                        $this->createMock(Canonical\Identity::class),
                        $this->createMock(HttpResource\Identity::class),
                        $this->createMock(HttpResource\Identity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                ),
            ],
            [
                new CitationAlreadyExist(
                    new Citation(
                        $this->createMock(Citation\Identity::class),
                        new Citation\Text('foo')
                    )
                ),
            ],
            [
                new CitationAppearanceAlreadyExist(
                    new CitationAppearance(
                        $this->createMock(CitationAppearance\Identity::class),
                        $this->createMock(Citation\Identity::class),
                        $this->createMock(HttpResource\Identity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                ),
            ],
            [
                new DomainAlreadyExist(
                    new Domain(
                        $this->createMock(Domain\Identity::class),
                        new Domain\Name('foo'),
                        new Domain\TopLevelDomain('com')
                    )
                ),
            ],
            [
                new HostAlreadyExist(
                    new Host(
                        $this->createMock(Host\Identity::class),
                        new Host\Name('foo.com')
                    )
                ),
            ],
            [new HtmlPageAlreadyExist],
            [new HttpResourceAlreadyExist],
            [new ImageAlreadyExist],
            [
                new ReferenceAlreadyExist(
                    new Reference(
                        $this->createMock(Reference\Identity::class),
                        $this->createMock(HttpResource\Identity::class),
                        $this->createMock(HttpResource\Identity::class)
                    )
                ),
            ],
        ];
    }
}
