<?php
declare(strict_types = 1);

namespace Domain\Handler;

use Domain\{
    Command\RegisterImage,
    Entity\Image,
    Entity\HostResource,
    Entity\HttpResource\Identity as ResourceIdentity,
    Repository\ImageRepository,
    Repository\HostResourceRepository,
    Specification\HttpResource\Path,
    Specification\HttpResource\Query,
    Specification\HostResource\InResources,
    Specification\HostResource\Host,
    Exception\ImageAlreadyExist
};
use Innmind\TimeContinuum\Clock;
use Innmind\Immutable\Set;

final class RegisterImageHandler
{
    private ImageRepository $imageRepository;
    private HostResourceRepository $relationRepository;
    private Clock $clock;

    public function __construct(
        ImageRepository $imageRepository,
        HostResourceRepository $relationRepository,
        Clock $clock
    ) {
        $this->imageRepository = $imageRepository;
        $this->relationRepository = $relationRepository;
        $this->clock = $clock;
    }

    public function __invoke(RegisterImage $wished): void
    {
        $this->verifyResourceDoesntExist($wished);

        $image = Image::register(
            $wished->identity(),
            $wished->path(),
            $wished->query()
        );
        $relation = HostResource::create(
            $wished->relation(),
            $wished->host(),
            $wished->identity(),
            $this->clock->now()
        );

        $this->imageRepository->add($image);
        $this->relationRepository->add($relation);
    }

    /**
     * @throws ImageAlreadyExist
     */
    private function verifyResourceDoesntExist(RegisterImage $wished): void
    {
        /** @psalm-suppress InvalidArgument */
        $images = $this->imageRepository->matching(
            (new Path($wished->path()))
                ->and(new Query($wished->query()))
        );

        if ($images->size() === 0) {
            return;
        }

        /** @var Set<ResourceIdentity> */
        $identities = $images->reduce(
            Set::of(ResourceIdentity::class),
            function(Set $identities, Image $image): Set {
                return $identities->add($image->identity());
            }
        );
        /** @psalm-suppress InvalidArgument */
        $relations = $this->relationRepository->matching(
            (new InResources($identities))
                ->and(new Host($wished->host()))
        );

        if ($relations->size() > 0) {
            throw new ImageAlreadyExist;
        }
    }
}
