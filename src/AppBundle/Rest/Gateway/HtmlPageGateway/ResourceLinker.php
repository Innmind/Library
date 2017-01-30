<?php
declare(strict_types = 1);

namespace AppBundle\Rest\Gateway\HtmlPageGateway;

use AppBundle\Entity\{
    Alternate\Identity,
    HttpResource\Identity as ResourceIdentity
};
use Domain\{
    Command\RegisterAlternateResource,
    Model\Language,
    Exception\AlternateAlreadyExistException
};
use Innmind\Rest\Server\{
    ResourceLinkerInterface,
    Reference
};
use Innmind\CommandBus\CommandBusInterface;
use Innmind\Http\Exception\Http\BadRequestException;
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
    public function __invoke(Reference $from, MapInterface $tos)
    {
        $definition = $from->definition();
        $from = new ResourceIdentity((string) $from->identity());

        $tos
            ->foreach(function(Reference $to, MapInterface $parameters) use ($definition) {
                if ($to->definition() !== $definition) {
                    throw new BadRequestException;
                }
            })
            ->foreach(function(Reference $to, MapInterface $parameters) use ($from) {
                switch ($parameters->get('rel')->value()) {
                    case 'alternate':
                        $this->registerAlternate(
                            $from,
                            $to,
                            $parameters->get('language')->value()
                        );
                        break;
                }
            });
    }

    private function registerAlternate(
        ResourceIdentity $from,
        Reference $to,
        string $language
    ): void {
        try {
            $this->commandBus->handle(
                new RegisterAlternateResource(
                    new Identity((string) Uuid::uuid4()),
                    $from,
                    new ResourceIdentity((string) $to->identity()),
                    new Language($language)
                )
            );
        } catch (AlternateAlreadyExistException $e) {
            //pass
        }
    }
}
