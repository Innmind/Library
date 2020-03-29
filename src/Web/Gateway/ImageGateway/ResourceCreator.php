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
        $identity = $this->registerImage($resource, $host);
        $this->specifyDimension($resource, $identity);
        $this->specifyWeight($resource, $identity);
        $this->specifyDescriptions($resource, $identity);

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

    private function registerImage(
        HttpResource $resource,
        HostIdentity $host
    ): Identity {
        $query = $resource->property('query')->value();

        ($this->handle)(
            new RegisterImage(
                $identity = new Identity((string) Uuid::uuid4()),
                $host,
                new HostResourceIdentity((string) Uuid::uuid4()),
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

        $resource
            ->property('descriptions')
            ->value()
            ->foreach(function(string $description) use ($identity): void {
                ($this->handle)(
                    new AddDescription(
                        $identity,
                        new Description($description)
                    )
                );
            });
    }
}
