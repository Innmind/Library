<?php
declare(strict_types = 1);

namespace Web\Gateway\HtmlPageGateway;

use App\Entity\{
    Alternate\Identity as AlternateIdentity,
    HttpResource\Identity as ResourceIdentity,
    Canonical\Identity as CanonicalIdentity,
};
use Domain\{
    Command\RegisterAlternateResource,
    Command\MakeCanonicalLink,
    Model\Language,
    Exception\AlternateAlreadyExist,
    Exception\CanonicalAlreadyExist,
};
use Innmind\Rest\Server\{
    ResourceLinker as ResourceLinkerInterface,
    Reference,
    Link,
};
use Innmind\CommandBus\CommandBus;
use Ramsey\Uuid\Uuid;

final class ResourceLinker implements ResourceLinkerInterface
{
    private $handle;

    public function __construct(CommandBus $handle)
    {
        $this->handle = $handle;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Reference $from, Link ...$links): void
    {
        $from = new ResourceIdentity((string) $from->identity());

        foreach ($links as $link) {
            switch ($link->relationship()) {
                case 'alternate':
                    $this->registerAlternate(
                        $from,
                        $link->reference(),
                        $link->get('language')->value()
                    );
                    break;

                case 'canonical':
                    $this->registerCanonical($from, $link->reference());
                    break;
            }
        }
    }

    private function registerAlternate(
        ResourceIdentity $from,
        Reference $to,
        string $language
    ): void {
        try {
            ($this->handle)(
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
            ($this->handle)(
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
