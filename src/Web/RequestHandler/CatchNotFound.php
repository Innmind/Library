<?php
declare(strict_types = 1);

namespace Web\RequestHandler;

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
    StatusCode\StatusCode,
};

final class CatchNotFound implements RequestHandler
{
    private $handle;

    public function __construct(RequestHandler $handle)
    {
        $this->handle = $handle;
    }

    public function __invoke(ServerRequest $request): Response
    {
        try {
            return ($this->handle)($request);
        } catch ( AlternateNotFound | AuthorNotFound | CanonicalNotFound | CitationNotFound | CitationAppearanceNotFound | DomainNotFound | HostNotFound | HtmlPageNotFound | HttpResourceNotFound | ImageNotFound | ReferenceNotFound $e) {
            return new Response\Response(
                $code = StatusCode::of('NOT_FOUND'),
                $code->associatedReasonPhrase(),
                $request->protocolVersion()
            );
        }
    }
}
