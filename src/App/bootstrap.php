<?php
declare(strict_types = 1);

namespace App;

use function Innmind\HttpTransport\bootstrap as http;
use function Innmind\Neo4j\DBAL\bootstrap as dbal;
use function Innmind\Neo4j\ONM\bootstrap as onm;
use function Innmind\CommandBus\bootstrap as commandBus;
use function Innmind\EventBus\bootstrap as eventBus;
use function Innmind\Logger\bootstrap as logger;
use Innmind\Neo4j\ONM\{
    Identity\Generator\UuidGenerator,
    Identity\Generator,
    Type\PointInTimeType,
};
use Innmind\TimeContinuum\{
    TimeContinuum\Earth,
    Timezone\Earth\UTC,
};
use Innmind\Url\UrlInterface;
use Innmind\Reflection\{
    ExtractionStrategy\ReflectionStrategy as ExtractionStrategy,
    InjectionStrategy\ReflectionStrategy as InjectionStrategy,
    Instanciator\ConstructorLessInstanciator,
};
use Innmind\Filesystem\Adapter;
use Innmind\Immutable\{
    Map,
    SetInterface,
    Set,
};
use Symfony\Component\Yaml\Yaml;
use Pdp;
use Psr\Log\{
    LoggerInterface,
    NullLogger,
};

/**
 * @param SetInterface<UrlInterface>|null $dsns
 */
function bootstrap(
    UrlInterface $neo4j,
    Adapter $domainEventStore,
    SetInterface $dsns = null,
    string $activationLevel = null
): array {
    $dsns = $dsns ?? Set::of(UrlInterface::class);
    $domainParser = (new Pdp\Manager(
        new Pdp\Cache,
        new class implements Pdp\HttpClient {
            public function getContent(string $url): string
            {
                return file_get_contents($url);
            }
        }
    ))->getRules();

    $clock = new Earth(new UTC);
    $http = http();
    $log = $http['logger'](logger('http', ...$dsns)($activationLevel));
    $httpTransport = $log(
        $http['catch_guzzle_exceptions'](
            $http['guzzle']()
        )
    );

    $eventBuses = eventBus();
    $eventBus = $eventBuses['bus'](
        (new Map('string', SetInterface::class))
            ->put('Domain\Event\*', Set::of(
                'callable',
                new Listener\StoreDomainEventListener($domainEventStore)
            ))
    );

    $dbal = dbal(
        $httpTransport,
        $clock,
        (string) $neo4j->scheme(),
        (string) $neo4j->authority()->host(),
        $neo4j->authority()->port()->value(),
        (string) $neo4j->authority()->userInformation()->user(),
        (string) $neo4j->authority()->userInformation()->password()
    );
    $onm = onm(
        $dbal,
        [
            Yaml::parseFile(__DIR__.'/config/neo4j/author.yml'),
            Yaml::parseFile(__DIR__.'/config/neo4j/citation.yml'),
            Yaml::parseFile(__DIR__.'/config/neo4j/domain.yml'),
            Yaml::parseFile(__DIR__.'/config/neo4j/host.yml'),
            Yaml::parseFile(__DIR__.'/config/neo4j/html_page.yml'),
            Yaml::parseFile(__DIR__.'/config/neo4j/http_resource.yml'),
            Yaml::parseFile(__DIR__.'/config/neo4j/image.yml'),
        ],
        Set::of(
            'string',
            Neo4j\Type\Author\NameType::class,
            Neo4j\Type\Citation\TextType::class,
            Neo4j\Type\Domain\NameType::class,
            Neo4j\Type\Domain\TopLevelDomainType::class,
            Neo4j\Type\Host\NameType::class,
            Neo4j\Type\HtmlPage\AnchorType::class,
            Neo4j\Type\HttpResource\CharsetType::class,
            Neo4j\Type\HttpResource\PathType::class,
            Neo4j\Type\HttpResource\QueryType::class,
            Neo4j\Type\Image\DescriptionType::class,
            Neo4j\Type\Image\DimensionType::class,
            Neo4j\Type\Image\WeightType::class,
            Neo4j\Type\ColourType::class,
            Neo4j\Type\LanguageType::class,
            Neo4j\Type\UrlType::class,
            PointInTimeType::class
        ),
        (new Map('string', Generator::class))
            ->put(Entity\Alternate\Identity::class, new UuidGenerator(Entity\Alternate\Identity::class))
            ->put(Entity\Author\Identity::class, new UuidGenerator(Entity\Author\Identity::class))
            ->put(Entity\Canonical\Identity::class, new UuidGenerator(Entity\Canonical\Identity::class))
            ->put(Entity\Citation\Identity::class, new UuidGenerator(Entity\Citation\Identity::class))
            ->put(Entity\CitationAppearance\Identity::class, new UuidGenerator(Entity\CitationAppearance\Identity::class))
            ->put(Entity\Domain\Identity::class, new UuidGenerator(Entity\Domain\Identity::class))
            ->put(Entity\DomainHost\Identity::class, new UuidGenerator(Entity\DomainHost\Identity::class))
            ->put(Entity\Host\Identity::class, new UuidGenerator(Entity\Host\Identity::class))
            ->put(Entity\HostResource\Identity::class, new UuidGenerator(Entity\HostResource\Identity::class))
            ->put(Entity\HtmlPage\Identity::class, new UuidGenerator(Entity\HtmlPage\Identity::class))
            ->put(Entity\HttpResource\Identity::class, new UuidGenerator(Entity\HttpResource\Identity::class))
            ->put(Entity\Image\Identity::class, new UuidGenerator(Entity\Image\Identity::class))
            ->put(Entity\Reference\Identity::class, new UuidGenerator(Entity\Reference\Identity::class))
            ->put(Entity\ResourceAuthor\Identity::class, new UuidGenerator(Entity\ResourceAuthor\Identity::class)),
        new ExtractionStrategy,
        new InjectionStrategy,
        new ConstructorLessInstanciator,
        $eventBus
    );

    $authorRepository = new Repository\Neo4j\AuthorRepository(
        $onm['manager']->repository(\Domain\Entity\Author::class)
    );
    $resourceAuthorRepository = new Repository\Neo4j\ResourceAuthorRepository(
        $onm['manager']->repository(\Domain\Entity\ResourceAuthor::class)
    );
    $citationRepository = new Repository\Neo4j\CitationRepository(
        $onm['manager']->repository(\Domain\Entity\Citation::class)
    );
    $citationAppearanceRepository = new Repository\Neo4j\CitationAppearanceRepository(
        $onm['manager']->repository(\Domain\Entity\CitationAppearance::class)
    );
    $domainRepository = new Repository\Neo4j\DomainRepository(
        $onm['manager']->repository(\Domain\Entity\Domain::class)
    );
    $domainHostRepository = new Repository\Neo4j\DomainHostRepository(
        $onm['manager']->repository(\Domain\Entity\DomainHost::class)
    );
    $hostRepository = new Repository\Neo4j\HostRepository(
        $onm['manager']->repository(\Domain\Entity\Host::class)
    );
    $hostResourceRepository = new Repository\Neo4j\HostResourceRepository(
        $onm['manager']->repository(\Domain\Entity\HostResource::class)
    );
    $httpResourceRepository = new Repository\Neo4j\HttpResourceRepository(
        $onm['manager']->repository(\Domain\Entity\HttpResource::class)
    );
    $imageRepository = new Repository\Neo4j\ImageRepository(
        $onm['manager']->repository(\Domain\Entity\Image::class)
    );
    $htmlPageRepository = new Repository\Neo4j\HtmlPageRepository(
        $onm['manager']->repository(\Domain\Entity\HtmlPage::class)
    );
    $alternateRepository = new Repository\Neo4j\AlternateRepository(
        $onm['manager']->repository(\Domain\Entity\Alternate::class)
    );
    $canonicalRepository = new Repository\Neo4j\CanonicalRepository(
        $onm['manager']->repository(\Domain\Entity\Canonical::class)
    );
    $referenceRepository = new Repository\Neo4j\ReferenceRepository(
        $onm['manager']->repository(\Domain\Entity\Reference::class)
    );

    $handlers = (new Map('string', 'callable'))
        ->put(
            \Domain\Command\RegisterAuthor::class,
            new \Domain\Handler\RegisterAuthorHandler($authorRepository)
        )
        ->put(
            \Domain\Command\RegisterCitation::class,
            new \Domain\Handler\RegisterCitationHandler($citationRepository)
        )
        ->put(
            \Domain\Command\Citation\RegisterAppearance::class,
            new \Domain\Handler\Citation\RegisterAppearanceHandler(
                $citationAppearanceRepository,
                $clock
            )
        )
        ->put(
            \Domain\Command\RegisterDomain::class,
            new \Domain\Handler\RegisterDomainHandler(
                $domainRepository,
                $domainParser
            )
        )
        ->put(
            \Domain\Command\RegisterHost::class,
            new \Domain\Handler\RegisterHostHandler(
                $hostRepository,
                $domainHostRepository,
                $clock
            )
        )
        ->put(
            \Domain\Command\RegisterHttpResource::class,
            new \Domain\Handler\RegisterHttpResourceHandler(
                $httpResourceRepository,
                $hostResourceRepository,
                $clock
            )
        )
        ->put(
            \Domain\Command\HttpResource\SpecifyCharset::class,
            new \Domain\Handler\HttpResource\SpecifyCharsetHandler($httpResourceRepository)
        )
        ->put(
            \Domain\Command\HttpResource\SpecifyLanguages::class,
            new \Domain\Handler\HttpResource\SpecifyLanguagesHandler($httpResourceRepository)
        )
        ->put(
            \Domain\Command\HttpResource\RegisterAuthor::class,
            new \Domain\Handler\HttpResource\RegisterAuthorHandler(
                $resourceAuthorRepository,
                $clock
            )
        )
        ->put(
            \Domain\Command\RegisterImage::class,
            new \Domain\Handler\RegisterImageHandler(
                $imageRepository,
                $hostResourceRepository,
                $clock
            )
        )
        ->put(
            \Domain\Command\Image\SpecifyDimension::class,
            new \Domain\Handler\Image\SpecifyDimensionHandler($imageRepository)
        )
        ->put(
            \Domain\Command\Image\SpecifyWeight::class,
            new \Domain\Handler\Image\SpecifyWeightHandler($imageRepository)
        )
        ->put(
            \Domain\Command\Image\AddDescription::class,
            new \Domain\Handler\Image\AddDescriptionHandler($imageRepository)
        )
        ->put(
            \Domain\Command\RegisterHtmlPage::class,
            new \Domain\Handler\RegisterHtmlPageHandler(
                $htmlPageRepository,
                $hostResourceRepository,
                $clock
            )
        )
        ->put(
            \Domain\Command\HtmlPage\FlagAsJournal::class,
            new \Domain\Handler\HtmlPage\FlagAsJournalHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyAnchors::class,
            new \Domain\Handler\HtmlPage\SpecifyAnchorsHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyAndroidAppLink::class,
            new \Domain\Handler\HtmlPage\SpecifyAndroidAppLinkHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyIosAppLink::class,
            new \Domain\Handler\HtmlPage\SpecifyIosAppLinkHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyPreview::class,
            new \Domain\Handler\HtmlPage\SpecifyPreviewHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyDescription::class,
            new \Domain\Handler\HtmlPage\SpecifyDescriptionHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyMainContent::class,
            new \Domain\Handler\HtmlPage\SpecifyMainContentHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyThemeColour::class,
            new \Domain\Handler\HtmlPage\SpecifyThemeColourHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\HtmlPage\SpecifyTitle::class,
            new \Domain\Handler\HtmlPage\SpecifyTitleHandler($htmlPageRepository)
        )
        ->put(
            \Domain\Command\RegisterAlternateResource::class,
            new \Domain\Handler\RegisterAlternateResourceHandler($alternateRepository)
        )
        ->put(
            \Domain\Command\MakeCanonicalLink::class,
            new \Domain\Handler\MakeCanonicalLinkHandler(
                $canonicalRepository,
                $clock
            )
        )
        ->put(
            \Domain\Command\ReferResource::class,
            new \Domain\Handler\ReferResourceHandler($referenceRepository)
        );

    $commandBuses = commandBus();
    $log = $commandBuses['logger'](
        logger('app', ...$dsns)($activationLevel)
    );

    $commandBus = $log(
        $onm['command_bus']['clear_domain_events'](
            $onm['command_bus']['dispatch_domain_events'](
                $onm['command_bus']['flush'](
                    $commandBuses['bus']($handlers)
                )
            )
        )
    );

    return [
        'command_bus' => $commandBus,
        // those repositories will be needed for the rest gateways, these should
        // not be exposed and replaced instead by a query bus
        'repository' => [
            'http_resource' => $httpResourceRepository,
            'image' => $imageRepository,
            'html_page' => $htmlPageRepository,
        ],
        'dbal' => $dbal,
    ];
}
