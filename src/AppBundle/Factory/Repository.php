<?php
declare(strict_types = 1);

namespace AppBundle\Factory;

use Innmind\Neo4j\ONM\{
    Manager,
    Repository as RepositoryInterface,
};

final class Repository
{
    public static function build(Manager $manager, string $repository): RepositoryInterface
    {
        return $manager->repository($repository);
    }
}
