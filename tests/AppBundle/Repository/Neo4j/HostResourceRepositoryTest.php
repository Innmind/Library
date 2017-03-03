<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\HostResourceRepository,
    Entity\HostResource\Identity
};
use Domain\{
    Repository\HostResourceRepositoryInterface,
    Entity\HostResource,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HttpResource\IdentityInterface as HttpResourceIdentity,
    Specification\HostResource\SpecificationInterface
};
use Innmind\Neo4j\ONM\{
    RepositoryInterface,
    Exception\EntityNotFoundException
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class HostResourceRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            HostResourceRepositoryInterface::class,
            new HostResourceRepository(
                $this->createMock(RepositoryInterface::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new HostResourceRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new HostResource(
                    $identity,
                    $this->createMock(HostIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(PointInTimeInterface::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    /**
     * @expectedException Domain\Exception\HostResourceNotFoundException
     */
    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new HostResourceRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->will(
                $this->throwException(new EntityNotFoundException)
            );

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new HostResourceRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $hostResource = new HostResource(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(HostIdentity::class),
            $this->createMock(HttpResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($hostResource);

        $this->assertSame($repository, $repository->add($hostResource));
    }

    public function testRemove()
    {
        $repository = new HostResourceRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $hostResource = new HostResource(
                    $identity,
                    $this->createMock(HostIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(PointInTimeInterface::class)
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($hostResource);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new HostResourceRepository(
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
        $repository = new HostResourceRepository(
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
        $repository = new HostResourceRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $hostResource = new HostResource(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(HostIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(HostResource::class, (string) $all->type());
        $this->assertSame([$hostResource], $all->toPrimitive());
    }

    public function testMatching()
    {
        $repository = new HostResourceRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $specification = $this->createMock(SpecificationInterface::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                (new Set('object'))->add(
                    $hostResource = new HostResource(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(HostIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(HostResource::class, (string) $all->type());
        $this->assertSame([$hostResource], $all->toPrimitive());
    }
}
