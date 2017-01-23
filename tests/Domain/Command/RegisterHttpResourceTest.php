<?php
declare(strict_types = 1);

namespace Tests\Domain\Command;

use Domain\{
    Command\RegisterHttpResource,
    Entity\HttpResource\IdentityInterface,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HostResource\IdentityInterface as RelationIdentity
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class RegisterHttpResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new RegisterHttpResource(
            $identity = $this->createMock(IdentityInterface::class),
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
