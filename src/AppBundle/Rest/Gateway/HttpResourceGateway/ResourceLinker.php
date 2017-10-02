<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\HttpResourceGateway;

use AppBundle\Entity\{
    Reference\Identity as ReferenceIdentity,
    HttpResource\Identity as ResourceIdentity
};
use Domain\{
    Command\ReferResource,
    Exception\ReferenceAlreadyExistException
};
use Innmind\Rest\Server\{
    ResourceLinker as ResourceLinkerInterface,
    Reference
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Http\Exception\Http\BadRequest;
use Innmind\Immutable\MapInterface;
use Ramsey\Uuid\Uuid;

final class ResourceLinker implements ResourceLinkerInterface
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Reference $from, MapInterface $tos): void
    {
        $definition = $from->definition();
        $from = new ResourceIdentity((string) $from->identity());

        $tos
            ->foreach(function(Reference $to, MapInterface $parameters) use ($definition) {
                if ($to->definition() !== $definition) {
                    throw new BadRequest;
                }
            })
            ->foreach(function(Reference $to, MapInterface $parameters) use ($from) {
                switch ($parameters->get('rel')->value()) {
                    case 'referrer':
                        $this->refer($from, $to);
                        break;
                }
            });
    }

    private function refer(ResourceIdentity $from, Reference $to): void
    {
        try {
            $this->commandBus->handle(
                new ReferResource(
                    new ReferenceIdentity((string) Uuid::uuid4()),
                    $from,
                    new ResourceIdentity((string) $to->identity())
                )
            );
        } catch (ReferenceAlreadyExistException $e) {
            //pass
        }
    }
}
