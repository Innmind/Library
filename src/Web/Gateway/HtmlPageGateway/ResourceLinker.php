<?php
declare(strict_types = 1);

namespace Web\Gateway\HtmlPageGateway;

use Web\Entity\{
    Alternate\Identity as AlternateIdentity,
    HttpResource\Identity as ResourceIdentity,
    Canonical\Identity as CanonicalIdentity
};
use Domain\{
    Command\RegisterAlternateResource,
    Command\MakeCanonicalLink,
    Model\Language,
    Exception\AlternateAlreadyExist,
    Exception\CanonicalAlreadyExist
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
                    case 'alternate':
                        $this->registerAlternate(
                            $from,
                            $to,
                            $parameters->get('language')->value()
                        );
                        break;

                    case 'canonical':
                        $this->registerCanonical($from, $to);
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
                    new AlternateIdentity((string) Uuid::uuid4()),
                    $from,
                    new ResourceIdentity((string) $to->identity()),
                    new Language($language)
                )
            );
        } catch (AlternateAlreadyExist $e) {
            //pass
        }
    }

    private function registerCanonical(ResourceIdentity $from, Reference $to): void
    {
        try {
            $this->commandBus->handle(
                new MakeCanonicalLink(
                    new CanonicalIdentity((string) Uuid::uuid4()),
                    new ResourceIdentity((string) $to->identity()),
                    $from
                )
            );
        } catch (CanonicalAlreadyExist $e) {
            //pass
        }
    }
}
