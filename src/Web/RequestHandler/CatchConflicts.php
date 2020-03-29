<?php
declare(strict_types = 1);

namespace Web\RequestHandler;

use Domain\Exception\{
    AlternateAlreadyExist,
    AuthorAlreadyExist,
    CanonicalAlreadyExist,
    CitationAlreadyExist,
    CitationAppearanceAlreadyExist,
    DomainAlreadyExist,
    HostAlreadyExist,
    HtmlPageAlreadyExist,
    HttpResourceAlreadyExist,
    ImageAlreadyExist,
    ReferenceAlreadyExist,
};
use Innmind\HttpFramework\RequestHandler;
use Innmind\Http\Message\{
    ServerRequest,
    Response,
    StatusCode\StatusCode,
};

final class CatchConflicts implements RequestHandler
{
    private RequestHandler $handle;

    public function __construct(RequestHandler $handle)
    {
        $this->handle = $handle;
    }

    public function __invoke(ServerRequest $request): Response
    {
        try {
            return ($this->handle)($request);
        } catch (AlternateAlreadyExist | AuthorAlreadyExist | CanonicalAlreadyExist | CitationAlreadyExist | CitationAppearanceAlreadyExist | DomainAlreadyExist | HostAlreadyExist | HtmlPageAlreadyExist | HttpResourceAlreadyExist | ImageAlreadyExist | ReferenceAlreadyExist $e) {
            return new Response\Response(
                $code = StatusCode::of('CONFLICT'),
                $code->associatedReasonPhrase(),
                $request->protocolVersion()
            );
        }
    }
}
