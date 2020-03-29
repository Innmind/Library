<?php
declare(strict_types = 1);

namespace Web\Gateway\HtmlPageGateway;

use App\Entity\{
    HtmlPage\Identity,
    HostResource\Identity as HostResourceIdentity,
    Domain\Identity as DomainIdentity,
    Host\Identity as HostIdentity,
    DomainHost\Identity as DomainHostIdentity,
    Author\Identity as AuthorIdentity,
    ResourceAuthor\Identity as ResourceAuthorIdentity,
    Citation\Identity as CitationIdentity,
    CitationAppearance\Identity as CitationAppearanceIdentity,
};
use Domain\{
    Command\RegisterDomain,
    Command\RegisterHost,
    Command\RegisterHtmlPage,
    Command\RegisterAuthor,
    Command\RegisterCitation,
    Command\HtmlPage\FlagAsJournal,
    Command\HtmlPage\SpecifyAnchors,
    Command\HtmlPage\SpecifyAndroidAppLink,
    Command\HtmlPage\SpecifyDescription,
    Command\HtmlPage\SpecifyIosAppLink,
    Command\HtmlPage\SpecifyMainContent,
    Command\HtmlPage\SpecifyThemeColour,
    Command\HtmlPage\SpecifyTitle,
    Command\HtmlPage\SpecifyPreview,
    Command\HttpResource\SpecifyCharset,
    Command\HttpResource\SpecifyLanguages,
    Command\HttpResource\RegisterAuthor as RegisterResourceAuthor,
    Command\Citation\RegisterAppearance,
    Exception\DomainAlreadyExist,
    Exception\HostAlreadyExist,
    Exception\AuthorAlreadyExist,
    Exception\CitationAlreadyExist,
    Entity\HttpResource\Charset,
    Entity\HtmlPage\Anchor,
    Entity\Author\Name as AuthorName,
    Entity\Citation\Text as CitationText,
    Model\Language,
};
use Innmind\Url\{
    Authority\Host,
    Path,
    Query,
    Url,
};
use Innmind\Colour\Colour;
use Innmind\Rest\Server\{
    ResourceCreator as ResourceCreatorInterface,
    Definition\HttpResource as ResourceDefinition,
    HttpResource,
    Identity as IdentityInterface,
};
use Innmind\CommandBus\CommandBus;
use Innmind\Immutable\Set;
use Ramsey\Uuid\Uuid;

final class ResourceCreator implements ResourceCreatorInterface
{
    private CommandBus $handle;

    public function __construct(CommandBus $handle)
    {
        $this->handle = $handle;
    }

    public function __invoke(
        ResourceDefinition $definition,
        HttpResource $resource
    ): IdentityInterface {
        $host = $this->registerHost($resource);
        $identity = $this->registerResource($resource, $host);
        $this->specifyCharset($resource, $identity);
        $this->specifyLanguages($resource, $identity);
        $this->registerAuthor($resource, $identity);
        $this->registerCitations($resource, $identity);
        $this->flagAsJournal($resource, $identity);
        $this->specifyAnchors($resource, $identity);
        $this->specifyAndroidAppLink($resource, $identity);
        $this->specifyDescription($resource, $identity);
        $this->specifyIosAppLink($resource, $identity);
        $this->specifyMainContent($resource, $identity);
        $this->specifyThemeColour($resource, $identity);
        $this->specifyTitle($resource, $identity);
        $this->specifyPreview($resource, $identity);

        return $identity;
    }

    private function registerHost(HttpResource $resource): HostIdentity
    {
        try {
            ($this->handle)(
                new RegisterDomain(
                    $domain = new DomainIdentity((string) Uuid::uuid4()),
                    $host = Host::of($resource->property('host')->value())
                )
            );
        } catch (DomainAlreadyExist $e) {
            $domain = $e->domain()->identity();
        }

        try {
            ($this->handle)(
                new RegisterHost(
                    $identity = new HostIdentity((string) Uuid::uuid4()),
                    $domain,
                    new DomainHostIdentity((string) Uuid::uuid4()),
                    $host
                )
            );
        } catch (HostAlreadyExist $e) {
            $identity = $e->host()->identity();
        }

        return $identity;
    }

    private function registerResource(
        HttpResource $resource,
        HostIdentity $host
    ): Identity {
        $query = $resource->property('query')->value();

        ($this->handle)(
            new RegisterHtmlPage(
                $identity = new Identity((string) Uuid::uuid4()),
                $host,
                new HostResourceIdentity((string) Uuid::uuid4()),
                Path::of($resource->property('path')->value()),
                empty($query) ? Query::none() : Query::of($query)
            )
        );

        return $identity;
    }

    private function specifyCharset(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('charset')) {
            return;
        }

        ($this->handle)(
            new SpecifyCharset(
                $identity,
                new Charset($resource->property('charset')->value())
            )
        );
    }

    private function specifyLanguages(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('languages')) {
            return;
        }

        $languages = $resource
            ->property('languages')
            ->value()
            ->mapTo(
                Language::class,
                static fn(string $language): Language => new Language($language),
            );

        ($this->handle)(
            new SpecifyLanguages(
                $identity,
                $languages
            )
        );
    }

    private function registerAuthor(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('author')) {
            return;
        }

        try {
            ($this->handle)(
                new RegisterAuthor(
                    $author = new AuthorIdentity((string) Uuid::uuid4()),
                    new AuthorName(
                        $resource->property('author')->value()
                    )
                )
            );
        } catch (AuthorAlreadyExist $e) {
            $author = $e->author()->identity();
        }

        ($this->handle)(
            new RegisterResourceAuthor(
                new ResourceAuthorIdentity((string) Uuid::uuid4()),
                $author,
                $identity
            )
        );
    }

    private function registerCitations(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('citations')) {
            return;
        }

        $resource
            ->property('citations')
            ->value()
            ->foreach(function(string $citation) use ($identity): void {
                $this->registerCitation($citation, $identity);
            });
    }

    private function registerCitation(string $citation, Identity $identity): void
    {
        try {
            ($this->handle)(
                new RegisterCitation(
                    $citationIdentity = new CitationIdentity((string) Uuid::uuid4()),
                    new CitationText($citation)
                )
            );
        } catch (CitationAlreadyExist $e) {
            $citationIdentity = $e->citation()->identity();
        }

        ($this->handle)(
            new RegisterAppearance(
                new CitationAppearanceIdentity((string) Uuid::uuid4()),
                $citationIdentity,
                $identity
            )
        );
    }

    private function flagAsJournal(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('is_journal')) {
            return;
        }

        ($this->handle)(
            new FlagAsJournal(
                $identity
            )
        );
    }

    private function specifyAnchors(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('anchors')) {
            return;
        }

        ($this->handle)(
            new SpecifyAnchors(
                $identity,
                $resource
                    ->property('anchors')
                    ->value()
                    ->reduce(
                        Set::of(Anchor::class),
                        function(Set $carry, string $anchor): Set {
                            return $carry->add(new Anchor($anchor));
                        }
                    )
            )
        );
    }

    private function specifyAndroidAppLink(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('android_app_link')) {
            return;
        }

        ($this->handle)(
            new SpecifyAndroidAppLink(
                $identity,
                Url::of(
                    $resource->property('android_app_link')->value()
                )
            )
        );
    }

    private function specifyDescription(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('description')) {
            return;
        }

        ($this->handle)(
            new SpecifyDescription(
                $identity,
                $resource->property('description')->value()
            )
        );
    }

    private function specifyIosAppLink(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('ios_app_link')) {
            return;
        }

        ($this->handle)(
            new SpecifyIosAppLink(
                $identity,
                Url::of(
                    $resource->property('ios_app_link')->value()
                )
            )
        );
    }

    private function specifyMainContent(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('main_content')) {
            return;
        }

        ($this->handle)(
            new SpecifyMainContent(
                $identity,
                $resource->property('main_content')->value()
            )
        );
    }

    private function specifyThemeColour(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('theme_colour')) {
            return;
        }

        $colour = Colour::of(
            $resource->property('theme_colour')->value()
        );

        ($this->handle)(
            new SpecifyThemeColour(
                $identity,
                $colour->toRGBA()
            )
        );
    }

    private function specifyTitle(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('title')) {
            return;
        }

        ($this->handle)(
            new SpecifyTitle(
                $identity,
                $resource->property('title')->value()
            )
        );
    }

    private function specifyPreview(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('preview')) {
            return;
        }

        ($this->handle)(
            new SpecifyPreview(
                $identity,
                Url::of(
                    $resource->property('preview')->value()
                )
            )
        );
    }
}
