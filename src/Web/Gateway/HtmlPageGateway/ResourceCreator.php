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
    Entity\Host\Identity as HostIdentityInterface,
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

    private function registerHost(HttpResource $resource): HostIdentityInterface
    {
        $domain = new DomainIdentity(Uuid::uuid4()->toString());
        /** @psalm-suppress MixedArgument */
        $host = Host::of($resource->property('host')->value());

        try {
            ($this->handle)(new RegisterDomain($domain, $host));
        } catch (DomainAlreadyExist $e) {
            $domain = $e->domain()->identity();
        }

        try {
            ($this->handle)(
                new RegisterHost(
                    $identity = new HostIdentity(Uuid::uuid4()->toString()),
                    $domain,
                    new DomainHostIdentity(Uuid::uuid4()->toString()),
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
        HostIdentityInterface $host
    ): Identity {
        /** @var string */
        $query = $resource->property('query')->value();

        /** @psalm-suppress MixedArgument */
        ($this->handle)(
            new RegisterHtmlPage(
                $identity = new Identity(Uuid::uuid4()->toString()),
                $host,
                new HostResourceIdentity(Uuid::uuid4()->toString()),
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

        /** @psalm-suppress MixedArgument */
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

        /** @var Set<string> */
        $languages = $resource->property('languages')->value();
        /** @var Set<Language> */
        $languages = $languages->mapTo(
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
            /** @psalm-suppress MixedArgument */
            ($this->handle)(
                new RegisterAuthor(
                    $author = new AuthorIdentity(Uuid::uuid4()->toString()),
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
                new ResourceAuthorIdentity(Uuid::uuid4()->toString()),
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

        /** @var Set<string> */
        $citations = $resource->property('citations')->value();
        $citations->foreach(function(string $citation) use ($identity): void {
            $this->registerCitation($citation, $identity);
        });
    }

    private function registerCitation(string $citation, Identity $identity): void
    {
        try {
            ($this->handle)(
                new RegisterCitation(
                    $citationIdentity = new CitationIdentity(Uuid::uuid4()->toString()),
                    new CitationText($citation)
                )
            );
        } catch (CitationAlreadyExist $e) {
            $citationIdentity = $e->citation()->identity();
        }

        ($this->handle)(
            new RegisterAppearance(
                new CitationAppearanceIdentity(Uuid::uuid4()->toString()),
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

        /** @var Set<string> */
        $anchors = $resource->property('anchors')->value();

        ($this->handle)(
            new SpecifyAnchors(
                $identity,
                $anchors->mapTo(
                    Anchor::class,
                    static fn(string $anchor): Anchor => new Anchor($anchor),
                ),
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

        /** @psalm-suppress MixedArgument */
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

        /** @psalm-suppress MixedArgument */
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

        /** @psalm-suppress MixedArgument */
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

        /** @psalm-suppress MixedArgument */
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

        /** @psalm-suppress MixedArgument */
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

        /** @psalm-suppress MixedArgument */
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

        /** @psalm-suppress MixedArgument */
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
