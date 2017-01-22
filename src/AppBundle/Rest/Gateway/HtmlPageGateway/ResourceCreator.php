<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\HtmlPageGateway;

use AppBundle\Entity\{
    HtmlPage\Identity,
    HostResource\Identity as HostResourceIdentity,
    Domain\Identity as DomainIdentity,
    Host\Identity as HostIdentity,
    DomainHost\Identity as DomainHostIdentity
};
use Domain\{
    Command\RegisterDomain,
    Command\RegisterHost,
    Command\RegisterHtmlPage,
    Command\HtmlPage\FlagAsJournal,
    Command\HtmlPage\SpecifyAnchors,
    Command\HtmlPage\SpecifyAndroidAppLink,
    Command\HtmlPage\SpecifyDescription,
    Command\HtmlPage\SpecifyIosAppLink,
    Command\HtmlPage\SpecifyMainContent,
    Command\HtmlPage\SpecifyThemeColour,
    Command\HtmlPage\SpecifyTitle,
    Command\HttpResource\SpecifyCharset,
    Command\HttpResource\SpecifyLanguages,
    Exception\DomainAlreadyExistException,
    Exception\HostAlreadyExistException,
    Entity\HttpResource\Charset,
    Entity\HtmlPage\Anchor,
    Model\Language
};
use Innmind\Url\{
    Authority\Host,
    Path,
    Query,
    Url
};
use Innmind\Colour\Colour;
use Innmind\Rest\Server\{
    ResourceCreatorInterface,
    Definition\HttpResource as ResourceDefinition,
    HttpResourceInterface,
    IdentityInterface
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Immutable\Set;
use Ramsey\Uuid\Uuid;

final class ResourceCreator implements ResourceCreatorInterface
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(
        ResourceDefinition $definition,
        HttpResourceInterface $resource
    ): IdentityInterface {
        $host = $this->registerHost($resource);
        $identity = $this->registerResource($resource, $host);
        $this->specifyCharset($resource, $identity);
        $this->specifyLanguages($resource, $identity);
        $this->flagAsJournal($resource, $identity);
        $this->specifyAnchors($resource, $identity);
        $this->specifyAndroidAppLink($resource, $identity);
        $this->specifyDescription($resource, $identity);
        $this->specifyIosAppLink($resource, $identity);
        $this->specifyMainContent($resource, $identity);
        $this->specifyThemeColour($resource, $identity);
        $this->specifyTitle($resource, $identity);

        return $identity;
    }

    private function registerHost(HttpResourceInterface $resource): HostIdentity
    {
        try {
            $this->commandBus->handle(
                new RegisterDomain(
                    $domain = new DomainIdentity((string) Uuid::uuid4()),
                    $host = new Host($resource->property('host')->value())
                )
            );
        } catch (DomainAlreadyExistException $e) {
            $domain = $e->domain()->identity();
        }

        try {
            $this->commandBus->handle(
                new RegisterHost(
                    $identity = new HostIdentity((string) Uuid::uuid4()),
                    $domain,
                    new DomainHostIdentity((string) Uuid::uuid4()),
                    $host
                )
            );
        } catch (HostAlreadyExistException $e) {
            $identity = $e->host()->identity();
        }

        return $identity;
    }

    private function registerResource(
        HttpResourceInterface $resource,
        HostIdentity $host
    ): Identity {
        $this->commandBus->handle(
            new RegisterHtmlPage(
                $identity = new Identity((string) Uuid::uuid4()),
                $host,
                new HostResourceIdentity((string) Uuid::uuid4()),
                new Path($resource->property('path')->value()),
                new Query($resource->property('query')->value())
            )
        );

        return $identity;
    }

    private function specifyCharset(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('charset')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyCharset(
                $identity,
                new Charset($resource->property('charset')->value())
            )
        );
    }

    private function specifyLanguages(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('languages')) {
            return;
        }

        $languages = new Set(Language::class);

        foreach ($resource->property('languages')->value() as $language) {
            $languages = $languages->add(new Language($language));
        }

        $this->commandBus->handle(
            new SpecifyLanguages(
                $identity,
                $languages
            )
        );
    }

    private function flagAsJournal(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('is_journal')) {
            return;
        }

        $this->commandBus->handle(
            new FlagAsJournal(
                $identity
            )
        );
    }

    private function specifyAnchors(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('anchors')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyAnchors(
                $identity,
                $resource
                    ->property('anchors')
                    ->value()
                    ->reduce(
                        new Set(Anchor::class),
                        function(Set $carry, string $anchor): Set {
                            return $carry->add(new Anchor($anchor));
                        }
                    )
            )
        );
    }

    private function specifyAndroidAppLink(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('android_app_link')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyAndroidAppLink(
                $identity,
                Url::fromString(
                    $resource->property('android_app_link')->value()
                )
            )
        );
    }

    private function specifyDescription(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('description')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyDescription(
                $identity,
                $resource->property('description')->value()
            )
        );
    }

    private function specifyIosAppLink(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('ios_app_link')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyIosAppLink(
                $identity,
                Url::fromString(
                    $resource->property('ios_app_link')->value()
                )
            )
        );
    }

    private function specifyMainContent(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('main_content')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyMainContent(
                $identity,
                $resource->property('main_content')->value()
            )
        );
    }

    private function specifyThemeColour(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('theme_colour')) {
            return;
        }

        $colour = Colour::fromString(
            $resource->property('theme_colour')->value()
        );

        $this->commandBus->handle(
            new SpecifyThemeColour(
                $identity,
                $colour->toRGBA()
            )
        );
    }

    private function specifyTitle(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('title')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyTitle(
                $identity,
                $resource->property('title')->value()
            )
        );
    }
}
