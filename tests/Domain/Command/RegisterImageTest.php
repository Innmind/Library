<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterImage,
    Entity\Image\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HostResource\Identity as RelationIdentity
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class RegisterImageTest extends TestCase
{
    public function testInterface()
    {
        $command = new RegisterImage(
            $identity = $this->createMock(Identity::class),
            $host = $this->createMock(HostIdentity::class),
            $relation = $this->createMock(RelationIdentity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($host, $command->host());
        $this->assertSame($relation, $command->relation());
        $this->assertSame($path, $command->path());
        $this->assertSame($query, $command->query());
    }
}
