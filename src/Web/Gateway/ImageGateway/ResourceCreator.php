<?php
declare(strict_types = 1);

namespace Web\Gateway\ImageGateway;

use App\Entity\{
    Image\Identity,
    HostResource\Identity as HostResourceIdentity,
    Domain\Identity as DomainIdentity,
    Host\Identity as HostIdentity,
    DomainHost\Identity as DomainHostIdentity,
};
use Domain\{
    Command\RegisterDomain,
    Command\RegisterHost,
    Command\RegisterImage,
    Command\Image\SpecifyDimension,
    Command\Image\SpecifyWeight,
    Command\Image\AddDescription,
    Exception\DomainAlreadyExist,
    Exception\HostAlreadyExist,
    Entity\Image\Dimension,
    Entity\Image\Weight,
    Entity\Image\Description,
    Entity\Host\Identity as HostIdentityInterface,
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
use Innmind\Immutable\{
    Set,
    Map,
};
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
        $identity = $this->registerImage($resource, $host);
        $this->specifyDimension($resource, $identity);
        $this->specifyWeight($resource, $identity);
        $this->specifyDescriptions($resource, $identity);

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

    private function registerImage(
        HttpResource $resource,
        HostIdentityInterface $host
    ): Identity {
        /** @var string */
        $query = $resource->property('query')->value();

        /** @psalm-suppress MixedArgument */
        ($this->handle)(
            new RegisterImage(
                $identity = new Identity(Uuid::uuid4()->toString()),
                $host,
                new HostResourceIdentity(Uuid::uuid4()->toString()),
                Path::of($resource->property('path')->value()),
                empty($query) ? Query::none() : Query::of($query)
            )
        );

        return $identity;
    }

    private function specifyDimension(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('dimension')) {
            return;
        }

        /** @var Map<string, int> */
        $dimension = $resource->property('dimension')->value();

        ($this->handle)(
            new SpecifyDimension(
                $identity,
                new Dimension(
                    $dimension->get('width'),
                    $dimension->get('height')
                )
            )
        );
    }

    private function specifyWeight(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('weight')) {
            return;
        }

        /** @psalm-suppress MixedArgument */
        ($this->handle)(
            new SpecifyWeight(
                $identity,
                new Weight($resource->property('weight')->value())
            )
        );
    }

    private function specifyDescriptions(
        HttpResource $resource,
        Identity $identity
    ): void {
        if (!$resource->has('descriptions')) {
            return;
        }

        /** @var Set<string> */
        $descriptions = $resource->property('descriptions')->value();
        $descriptions->foreach(function(string $description) use ($identity): void {
            ($this->handle)(
                new AddDescription(
                    $identity,
                    new Description($description)
                )
            );
        });
    }
}
