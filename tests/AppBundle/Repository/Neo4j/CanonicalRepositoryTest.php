<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\CanonicalRepository,
    Entity\Canonical\Identity
};
use Domain\{
    Repository\CanonicalRepositoryInterface,
    Entity\Canonical,
    Entity\HttpResource\IdentityInterface as HttpResourceIdentity,
    Specification\Canonical\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};
use Ramsey\Uuid\Uuid;

class CanonicalRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CanonicalRepositoryInterface::class,
            new CanonicalRepository(
                $this->createMock(RepositoryInterface::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(RepositoryInterface::class)
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

    public function testAdd()
    {
        $repository = new CanonicalRepository(
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $specification = $this->createMock(SpecificationInterface::class);
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
