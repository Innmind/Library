<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\DomainHostRepository,
    Entity\DomainHost\Identity,
};
use Domain\{
    Repository\DomainHostRepository as DomainHostRepositoryInterface,
    Entity\DomainHost,
    Entity\Domain\Identity as DomainIdentity,
    Entity\Host\Identity as HostIdentity,
    Specification\DomainHost\Specification,
    Exception\DomainHostNotFound,
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\Immutable\Set;
use function Innmind\Immutable\unwrap;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class DomainHostRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            DomainHostRepositoryInterface::class,
            new DomainHostRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new DomainHostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new DomainHost(
                    $identity,
                    $this->createMock(DomainIdentity::class),
                    $this->createMock(HostIdentity::class),
                    $this->createMock(PointInTime::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new DomainHostRepository(
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

        $this->expectException(DomainHostNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new DomainHostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $entity = new DomainHost(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(DomainIdentity::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(PointInTime::class)
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $this->assertSame($repository, $repository->add($entity));
    }

    public function testRemove()
    {
        $repository = new DomainHostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $entity = new DomainHost(
                    $identity,
                    $this->createMock(DomainIdentity::class),
                    $this->createMock(HostIdentity::class),
                    $this->createMock(PointInTime::class)
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($entity);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new DomainHostRepository(
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
        $repository = new DomainHostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    DomainHost::class,
                    new DomainHost(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(DomainIdentity::class),
                        $this->createMock(HostIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $this->assertSame(1, $repository->count());
    }

    public function testAll()
    {
        $repository = new DomainHostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $entity = new DomainHost(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(DomainIdentity::class),
                        $this->createMock(HostIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(DomainHost::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new DomainHostRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $entity = new DomainHost(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(DomainIdentity::class),
                        $this->createMock(HostIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(DomainHost::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }
}
