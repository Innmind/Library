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
    return Map::of('string', 'callable')
        (
            Command\RegisterAuthor::class,
            new Handler\RegisterAuthorHandler($authorRepository)
        )
        (
            Command\RegisterCitation::class,
            new Handler\RegisterCitationHandler($citationRepository)
        )
        (
            Command\Citation\RegisterAppearance::class,
            new Handler\Citation\RegisterAppearanceHandler(
                $citationAppearanceRepository,
                $clock
            )
        )
        (
            Command\RegisterDomain::class,
            new Handler\RegisterDomainHandler(
                $domainRepository,
                $domainParser
            )
        )
        (
            Command\RegisterHost::class,
            new Handler\RegisterHostHandler(
                $hostRepository,
                $domainHostRepository,
                $clock
            )
        )
        (
            Command\RegisterHttpResource::class,
            new Handler\RegisterHttpResourceHandler(
                $httpResourceRepository,
                $hostResourceRepository,
                $clock
            )
        )
        (
            Command\HttpResource\SpecifyCharset::class,
            new Handler\HttpResource\SpecifyCharsetHandler($httpResourceRepository)
        )
        (
            Command\HttpResource\SpecifyLanguages::class,
            new Handler\HttpResource\SpecifyLanguagesHandler($httpResourceRepository)
        )
        (
            Command\HttpResource\RegisterAuthor::class,
            new Handler\HttpResource\RegisterAuthorHandler(
                $resourceAuthorRepository,
                $clock
            )
        )
        (
            Command\RegisterImage::class,
            new Handler\RegisterImageHandler(
                $imageRepository,
                $hostResourceRepository,
                $clock
            )
        )
        (
            Command\Image\SpecifyDimension::class,
            new Handler\Image\SpecifyDimensionHandler($imageRepository)
        )
        (
            Command\Image\SpecifyWeight::class,
            new Handler\Image\SpecifyWeightHandler($imageRepository)
        )
        (
            Command\Image\AddDescription::class,
            new Handler\Image\AddDescriptionHandler($imageRepository)
        )
        (
            Command\RegisterHtmlPage::class,
            new Handler\RegisterHtmlPageHandler(
                $htmlPageRepository,
                $hostResourceRepository,
                $clock
            )
        )
        (
            Command\HtmlPage\FlagAsJournal::class,
            new Handler\HtmlPage\FlagAsJournalHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyAnchors::class,
            new Handler\HtmlPage\SpecifyAnchorsHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyAndroidAppLink::class,
            new Handler\HtmlPage\SpecifyAndroidAppLinkHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyIosAppLink::class,
            new Handler\HtmlPage\SpecifyIosAppLinkHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyPreview::class,
            new Handler\HtmlPage\SpecifyPreviewHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyDescription::class,
            new Handler\HtmlPage\SpecifyDescriptionHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyMainContent::class,
            new Handler\HtmlPage\SpecifyMainContentHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyThemeColour::class,
            new Handler\HtmlPage\SpecifyThemeColourHandler($htmlPageRepository)
        )
        (
            Command\HtmlPage\SpecifyTitle::class,
            new Handler\HtmlPage\SpecifyTitleHandler($htmlPageRepository)
        )
        (
            Command\RegisterAlternateResource::class,
            new Handler\RegisterAlternateResourceHandler($alternateRepository)
        )
        (
            Command\MakeCanonicalLink::class,
            new Handler\MakeCanonicalLinkHandler(
                $canonicalRepository,
                $clock
            )
        )
        (
            Command\ReferResource::class,
            new Handler\ReferResourceHandler($referenceRepository)
        );
}
