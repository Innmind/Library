<?php
declare(strict_types = 1);

namespace Web\Gateway\HttpResourceGateway;

use App\Entity\{
    HttpResource\Identity,
    HostResource\Identity as HostResourceIdentity,
    Domain\Identity as DomainIdentity,
    Host\Identity as HostIdentity,
    DomainHost\Identity as DomainHostIdentity
};
use Domain\{
    Command\RegisterDomain,
    Command\RegisterHost,
    Command\RegisterHttpResource,
    Command\HttpResource\SpecifyCharset,
    Command\HttpResource\SpecifyLanguages,
    Exception\DomainAlreadyExist,
    Exception\HostAlreadyExist,
    Entity\HttpResource\Charset,
    Model\Language
};
use Innmind\Url\{
    Authority\Host,
    Path,
    Query,
    NullQuery
};
use Innmind\Rest\Server\{
    ResourceCreator as ResourceCreatorInterface,
    Definition\HttpResource as ResourceDefinition,
    HttpResource,
    Identity as IdentityInterface
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
        HttpResource $resource
    ): IdentityInterface {
        $host = $this->registerHost($resource);
        $identity = $this->registerResource($resource, $host);
        $this->specifyCharset($resource, $identity);
        $this->specifyLanguages($resource, $identity);

        return $identity;
    }

    private function registerHost(HttpResource $resource): HostIdentity
    {
        try {
            $this->commandBus->handle(
                new RegisterDomain(
                    $domain = new DomainIdentity((string) Uuid::uuid4()),
                    $host = new Host($resource->property('host')->value())
                )
            );
        } catch (DomainAlreadyExist $e) {
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

        $this->commandBus->handle(
            new RegisterHttpResource(
                $identity = new Identity((string) Uuid::uuid4()),
                $host,
                new HostResourceIdentity((string) Uuid::uuid4()),
                new Path($resource->property('path')->value()),
                empty($query) ? new NullQuery : new Query($query)
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

        $this->commandBus->handle(
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
}
