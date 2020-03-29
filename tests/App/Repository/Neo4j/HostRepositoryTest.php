<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\HostRepository,
    Entity\Host\Identity,
};
use Domain\{
    Repository\HostRepository as HostRepositoryInterface,
    Entity\Host,
    Entity\Host\Name,
    Specification\Host\Specification,
    Exception\HostNotFound,
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound,
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\unwrap;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class HostRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            HostRepositoryInterface::class,
            new HostRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Host(
                    $identity,
                    new Name('foo')
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->will(
                $this->throwException(new EntityNotFound)
            );

        $this->expectException(HostNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $host = new Host(
            new Identity((string) Uuid::uuid4()),
            new Name('foo')
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($host);

        $this->assertSame($repository, $repository->add($host));
    }

    public function testRemove()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $host = new Host(
                    $identity,
                    new Name('foo')
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($host);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->at(0))
            ->method('contains')
            ->with($identity)
            ->willReturn(true);
        $infra
            ->expects($this->at(1))
            ->method('contains')
            ->with($identity)
            ->willReturn(false);

        $this->assertTrue($repository->has($identity));
        $this->assertFalse($repository->has($identity));
    }

    public function testCount()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    Host::class,
                    new Host(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo')
                    )
                )
            );

        $this->assertSame(1, $repository->count());
    }

    public function testAll()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $host = new Host(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo')
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Host::class, (string) $all->type());
        $this->assertSame([$host], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $host = new Host(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo')
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Host::class, (string) $all->type());
        $this->assertSame([$host], unwrap($all));
    }
}
