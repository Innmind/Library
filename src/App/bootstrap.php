<?php
declare(strict_types = 1);

namespace App;

use function Domain\bootstrap as domain;
use function Innmind\HttpTransport\bootstrap as http;
use function Innmind\Neo4j\DBAL\bootstrap as dbal;
use function Innmind\Neo4j\ONM\bootstrap as onm;
use function Innmind\CommandBus\bootstrap as commandBus;
use function Innmind\EventBus\bootstrap as eventBus;
use function Innmind\Logger\bootstrap as logger;
use function Innmind\Stack\stack;
use Innmind\Neo4j\ONM\{
    Metadata,
    Identity\Generator\UuidGenerator,
    Identity\Generator,
};
use Innmind\TimeContinuum\{
    TimeContinuum\Earth,
    Timezone\Earth\UTC,
};
use Innmind\Url\UrlInterface;
use Innmind\Filesystem\Adapter;
use Innmind\HttpTransport\Transport;
use Innmind\Immutable\{
    Map,
    SetInterface,
    Set,
};
use Pdp;

/**
 * @param SetInterface<UrlInterface>|null $dsns
 */
function bootstrap(
    Transport $http,
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
                return \file_get_contents($url);
            }
        }
    ))->getRules();

    $clock = new Earth(new UTC);
    $log = http()['logger'](logger('http', ...$dsns)($activationLevel));
    $httpTransport = $log($http);

    $eventBuses = eventBus();
    $eventBus = $eventBuses['bus'](
        Map::of('string', 'callable')
            ('Domain\Event\*', new Listener\StoreDomainEventListener($domainEventStore))
    );

    $dbal = dbal(
        $httpTransport,
        $clock,
        $neo4j
    );
    $onm = onm(
        $dbal,
        Set::of(
            Metadata\Entity::class,
            ... (require __DIR__.'/config/neo4j.php')
        ),
        Map::of('string', Generator::class)
            (Entity\Alternate\Identity::class, new UuidGenerator(Entity\Alternate\Identity::class))
            (Entity\Author\Identity::class, new UuidGenerator(Entity\Author\Identity::class))
            (Entity\Canonical\Identity::class, new UuidGenerator(Entity\Canonical\Identity::class))
            (Entity\Citation\Identity::class, new UuidGenerator(Entity\Citation\Identity::class))
            (Entity\CitationAppearance\Identity::class, new UuidGenerator(Entity\CitationAppearance\Identity::class))
            (Entity\Domain\Identity::class, new UuidGenerator(Entity\Domain\Identity::class))
            (Entity\DomainHost\Identity::class, new UuidGenerator(Entity\DomainHost\Identity::class))
            (Entity\Host\Identity::class, new UuidGenerator(Entity\Host\Identity::class))
            (Entity\HostResource\Identity::class, new UuidGenerator(Entity\HostResource\Identity::class))
            (Entity\HtmlPage\Identity::class, new UuidGenerator(Entity\HtmlPage\Identity::class))
            (Entity\HttpResource\Identity::class, new UuidGenerator(Entity\HttpResource\Identity::class))
            (Entity\Image\Identity::class, new UuidGenerator(Entity\Image\Identity::class))
            (Entity\Reference\Identity::class, new UuidGenerator(Entity\Reference\Identity::class))
            (Entity\ResourceAuthor\Identity::class, new UuidGenerator(Entity\ResourceAuthor\Identity::class)),
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

    $handlers = domain(
        $authorRepository,
        $citationRepository,
        $citationAppearanceRepository,
        $domainRepository,
        $hostRepository,
        $domainHostRepository,
        $hostResourceRepository,
        $httpResourceRepository,
        $resourceAuthorRepository,
        $imageRepository,
        $htmlPageRepository,
        $alternateRepository,
        $canonicalRepository,
        $referenceRepository,
        $domainParser,
        $clock
    );

    $commandBuses = commandBus();
    $log = $commandBuses['logger'](
        logger('app', ...$dsns)($activationLevel)
    );

    $commandBus = stack(
        $log,
        $onm['command_bus']['clear_domain_events'],
        $onm['command_bus']['dispatch_domain_events'],
        $onm['command_bus']['flush'],
        $commandBuses['bus']
    )($handlers);

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
