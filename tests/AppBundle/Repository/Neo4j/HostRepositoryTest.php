<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\HostRepository,
    Entity\Host\Identity
};
use Domain\{
    Repository\HostRepositoryInterface,
    Entity\Host,
    Entity\Host\Name,
    Specification\Host\SpecificationInterface
};
use Innmind\Neo4j\ONM\{
    RepositoryInterface,
    Exception\EntityNotFoundException
};
use Innmind\Immutable\{
    SetInterface,
    Set
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class HostRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            HostRepositoryInterface::class,
            new HostRepository(
                $this->createMock(RepositoryInterface::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(RepositoryInterface::class)
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

    /**
     * @expectedException Domain\Exception\HostNotFoundException
     */
    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new HostRepository(
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
        $repository = new HostRepository(
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
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
        $repository = new HostRepository(
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
        $repository = new HostRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $host = new Host(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo')
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Host::class, (string) $all->type());
        $this->assertSame([$host], $all->toPrimitive());
    }

    public function testMatching()
    {
        $repository = new HostRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $specification = $this->createMock(SpecificationInterface::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                (new Set('object'))->add(
                    $host = new Host(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo')
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Host::class, (string) $all->type());
        $this->assertSame([$host], $all->toPrimitive());
    }
}
