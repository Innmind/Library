<?php
declare(strict_types = 1);

namespace Web\Gateway\HttpResourceGateway;

use App\Entity\{
    HttpResource\Identity,
    HostResource\Identity as HostResourceIdentity,
    Domain\Identity as DomainIdentity,
    Host\Identity as HostIdentity,
    DomainHost\Identity as DomainHostIdentity,
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
    Entity\Host\Identity as HostIdentityInterface,
    Model\Language,
};
use Innmind\Url\{
    Authority\Host,
    Path,
    Query,
};
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
            new RegisterHttpResource(
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
}
