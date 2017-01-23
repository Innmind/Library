<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\ImageGateway;

use AppBundle\Entity\{
    Image\Identity,
    HostResource\Identity as HostResourceIdentity,
    Domain\Identity as DomainIdentity,
    Host\Identity as HostIdentity,
    DomainHost\Identity as DomainHostIdentity
};
use Domain\{
    Command\RegisterDomain,
    Command\RegisterHost,
    Command\RegisterImage,
    Command\Image\SpecifyDimension,
    Command\Image\SpecifyWeight,
    Command\Image\AddDescription,
    Exception\DomainAlreadyExistException,
    Exception\HostAlreadyExistException,
    Entity\Image\Dimension,
    Entity\Image\Weight,
    Entity\Image\Description
};
use Innmind\Url\{
    Authority\Host,
    Path,
    Query
};
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
        $identity = $this->registerImage($resource, $host);
        $this->specifyDimension($resource, $identity);
        $this->specifyWeight($resource, $identity);
        $this->specifyDescriptions($resource, $identity);

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

    private function registerImage(
        HttpResourceInterface $resource,
        HostIdentity $host
    ): Identity {
        $this->commandBus->handle(
            new RegisterImage(
                $identity = new Identity((string) Uuid::uuid4()),
                $host,
                new HostResourceIdentity((string) Uuid::uuid4()),
                new Path($resource->property('path')->value()),
                new Query($resource->property('query')->value())
            )
        );

        return $identity;
    }

    private function specifyDimension(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('dimension')) {
            return;
        }

        $dimension = $resource->property('dimension')->value();

        $this->commandBus->handle(
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
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('weight')) {
            return;
        }

        $this->commandBus->handle(
            new SpecifyWeight(
                $identity,
                new Weight($resource->property('weight')->value())
            )
        );
    }

    private function specifyDescriptions(
        HttpResourceInterface $resource,
        Identity $identity
    ): void {
        if (!$resource->has('descriptions')) {
            return;
        }

        $resource
            ->property('descriptions')
            ->value()
            ->foreach(function(string $description) use ($identity): void {
                $this->commandBus->handle(
                    new AddDescription(
                        $identity,
                        new Description($description)
                    )
                );
            });
    }
}
