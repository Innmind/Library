<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\CanonicalRepository,
    Entity\Canonical\Identity,
};
use Domain\{
    Repository\CanonicalRepository as CanonicalRepositoryInterface,
    Entity\Canonical,
    Entity\HttpResource\Identity as HttpResourceIdentity,
    Specification\Canonical\Specification,
    Exception\CanonicalNotFound,
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound,
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\Immutable\{
    SetInterface,
    Set,
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class CanonicalRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CanonicalRepositoryInterface::class,
            new CanonicalRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Canonical(
                    $identity,
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(PointInTimeInterface::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new CanonicalRepository(
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

        $this->expectException(CanonicalNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(Repository::class)
        );
        $entity = new Canonical(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(HttpResourceIdentity::class),
            $this->createMock(HttpResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $this->assertSame($repository, $repository->add($entity));
    }

    public function testRemove()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $entity = new Canonical(
                    $identity,
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(PointInTimeInterface::class)
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
        $repository = new CanonicalRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->at(0))
            ->method('has')
            ->with($identity)
            ->willReturn(true);
        $infra
            ->expects($this->at(1))
            ->method('has')
            ->with($identity)
            ->willReturn(false);

        $this->assertTrue($repository->has($identity));
        $this->assertFalse($repository->has($identity));
    }

    public function testCount()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                $all = $this->createMock(SetInterface::class)
            );
        $all
            ->expects($this->once())
            ->method('size')
            ->willReturn(42);

        $this->assertSame(42, $repository->count());
    }

    public function testAll()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $entity = new Canonical(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Canonical::class, (string) $all->type());
        $this->assertSame([$entity], $all->toPrimitive());
    }

    public function testMatching()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                (new Set('object'))->add(
                    $entity = new Canonical(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Canonical::class, (string) $all->type());
        $this->assertSame([$entity], $all->toPrimitive());
    }
}
