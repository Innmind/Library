<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\HttpResourceRepository,
    Entity\HttpResource\Identity
};
use Domain\{
    Repository\HttpResourceRepository as HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Specification\HttpResource\Specification
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\Immutable\{
    SetInterface,
    Set
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class HttpResourceRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            HttpResourceRepositoryInterface::class,
            new HttpResourceRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new HttpResourceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new HttpResource(
                    $identity,
                    $this->createMock(PathInterface::class),
                    $this->createMock(QueryInterface::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    /**
     * @expectedException Domain\Exception\HttpResourceNotFoundException
     */
    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new HttpResourceRepository(
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

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new HttpResourceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $resource = new HttpResource(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($resource);

        $this->assertSame($repository, $repository->add($resource));
    }

    public function testRemove()
    {
        $repository = new HttpResourceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $resource = new HttpResource(
                    $identity,
                    $this->createMock(PathInterface::class),
                    $this->createMock(QueryInterface::class)
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($resource);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new HttpResourceRepository(
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
        $repository = new HttpResourceRepository(
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
        $repository = new HttpResourceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $resource = new HttpResource(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(HttpResource::class, (string) $all->type());
        $this->assertSame([$resource], $all->toPrimitive());
    }

    public function testMatching()
    {
        $repository = new HttpResourceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                (new Set('object'))->add(
                    $resource = new HttpResource(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(HttpResource::class, (string) $all->type());
        $this->assertSame([$resource], $all->toPrimitive());
    }
}
