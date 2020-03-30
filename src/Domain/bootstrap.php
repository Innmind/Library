<?php
declare(strict_types = 1);

namespace Domain;

use Innmind\TimeContinuum\Clock;
use Innmind\Immutable\Map;

/**
 * @return Map<string, callable>
 */
function bootstrap(
    Repository\AuthorRepository $authorRepository,
    Repository\CitationRepository $citationRepository,
    Repository\CitationAppearanceRepository $citationAppearanceRepository,
    Repository\DomainRepository $domainRepository,
    Repository\HostRepository $hostRepository,
    Repository\DomainHostRepository $domainHostRepository,
    Repository\HostResourceRepository $hostResourceRepository,
    Repository\HttpResourceRepository $httpResourceRepository,
    Repository\ResourceAuthorRepository $resourceAuthorRepository,
    Repository\ImageRepository $imageRepository,
    Repository\HtmlPageRepository $htmlPageRepository,
    Repository\AlternateRepository $alternateRepository,
    Repository\CanonicalRepository $canonicalRepository,
    Repository\ReferenceRepository $referenceRepository,
    \Pdp\Rules $domainParser,
    Clock $clock
): Map {
    /** @var Map<string, callable> */
    $handlers = Map::of('string', 'callable');

    return ($handlers)
        ->put(
            Command\RegisterAuthor::class,
            new Handler\RegisterAuthorHandler($authorRepository)
        )
        ->put(
            Command\RegisterCitation::class,
            new Handler\RegisterCitationHandler($citationRepository)
        )
        ->put(
            Command\Citation\RegisterAppearance::class,
            new Handler\Citation\RegisterAppearanceHandler(
                $citationAppearanceRepository,
                $clock
            )
        )
        ->put(
            Command\RegisterDomain::class,
            new Handler\RegisterDomainHandler(
                $domainRepository,
                $domainParser
            )
        )
        ->put(
            Command\RegisterHost::class,
            new Handler\RegisterHostHandler(
                $hostRepository,
                $domainHostRepository,
                $clock
            )
        )
        ->put(
            Command\RegisterHttpResource::class,
            new Handler\RegisterHttpResourceHandler(
                $httpResourceRepository,
                $hostResourceRepository,
                $clock
            )
        )
        ->put(
            Command\HttpResource\SpecifyCharset::class,
            new Handler\HttpResource\SpecifyCharsetHandler($httpResourceRepository)
        )
        ->put(
            Command\HttpResource\SpecifyLanguages::class,
            new Handler\HttpResource\SpecifyLanguagesHandler($httpResourceRepository)
        )
        ->put(
            Command\HttpResource\RegisterAuthor::class,
            new Handler\HttpResource\RegisterAuthorHandler(
                $resourceAuthorRepository,
                $clock
            )
        )
        ->put(
            Command\RegisterImage::class,
            new Handler\RegisterImageHandler(
                $imageRepository,
                $hostResourceRepository,
                $clock
            )
        )
        ->put(
            Command\Image\SpecifyDimension::class,
            new Handler\Image\SpecifyDimensionHandler($imageRepository)
        )
        ->put(
            Command\Image\SpecifyWeight::class,
            new Handler\Image\SpecifyWeightHandler($imageRepository)
        )
        ->put(
            Command\Image\AddDescription::class,
            new Handler\Image\AddDescriptionHandler($imageRepository)
        )
        ->put(
            Command\RegisterHtmlPage::class,
            new Handler\RegisterHtmlPageHandler(
                $htmlPageRepository,
                $hostResourceRepository,
                $clock
            )
        )
        ->put(
            Command\HtmlPage\FlagAsJournal::class,
            new Handler\HtmlPage\FlagAsJournalHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyAnchors::class,
            new Handler\HtmlPage\SpecifyAnchorsHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyAndroidAppLink::class,
            new Handler\HtmlPage\SpecifyAndroidAppLinkHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyIosAppLink::class,
            new Handler\HtmlPage\SpecifyIosAppLinkHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyPreview::class,
            new Handler\HtmlPage\SpecifyPreviewHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyDescription::class,
            new Handler\HtmlPage\SpecifyDescriptionHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyMainContent::class,
            new Handler\HtmlPage\SpecifyMainContentHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyThemeColour::class,
            new Handler\HtmlPage\SpecifyThemeColourHandler($htmlPageRepository)
        )
        ->put(
            Command\HtmlPage\SpecifyTitle::class,
            new Handler\HtmlPage\SpecifyTitleHandler($htmlPageRepository)
        )
        ->put(
            Command\RegisterAlternateResource::class,
            new Handler\RegisterAlternateResourceHandler($alternateRepository)
        )
        ->put(
            Command\MakeCanonicalLink::class,
            new Handler\MakeCanonicalLinkHandler(
                $canonicalRepository,
                $clock
            )
        )
        ->put(
            Command\ReferResource::class,
            new Handler\ReferResourceHandler($referenceRepository)
        );
}
