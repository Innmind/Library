<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\HttpResourceRepository,
    Entity\HttpResource\Identity,
};
use Domain\{
    Repository\HttpResourceRepository as HttpResourceRepositoryInterface,
    Entity\HttpResource,
    Specification\HttpResource\Specification,
    Exception\HttpResourceNotFound,
};
use Innmind\Url\{
    Path,
    Query,
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound,
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\unwrap;
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
                    Path::none(),
                    Query::none()
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

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

        $this->expectException(HttpResourceNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new HttpResourceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $resource = new HttpResource(
            new Identity((string) Uuid::uuid4()),
            Path::none(),
            Query::none()
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
                    Path::none(),
                    Query::none()
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
        $repository = new HttpResourceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    HttpResource::class,
                    new HttpResource(
                        new Identity((string) Uuid::uuid4()),
                        Path::none(),
                        Query::none()
                    )
                )
            );

        $this->assertSame(1, $repository->count());
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
                Set::objects(
                    $resource = new HttpResource(
                        new Identity((string) Uuid::uuid4()),
                        Path::none(),
                        Query::none()
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(HttpResource::class, (string) $all->type());
        $this->assertSame([$resource], unwrap($all));
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
                Set::objects(
                    $resource = new HttpResource(
                        new Identity((string) Uuid::uuid4()),
                        Path::none(),
                        Query::none()
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(HttpResource::class, (string) $all->type());
        $this->assertSame([$resource], unwrap($all));
    }
}
