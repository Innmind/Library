<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\ResourceAuthorRepository,
    Entity\ResourceAuthor\Identity,
};
use Domain\{
    Repository\ResourceAuthorRepository as ResourceAuthorRepositoryInterface,
    Entity\ResourceAuthor,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as HttpResourceIdentity,
    Specification\ResourceAuthor\Specification,
    Exception\ResourceAuthorNotFound,
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

class ResourceAuthorRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ResourceAuthorRepositoryInterface::class,
            new ResourceAuthorRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new ResourceAuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new ResourceAuthor(
                    $identity,
                    $this->createMock(AuthorIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(PointInTime::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new ResourceAuthorRepository(
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

        $this->expectException(ResourceAuthorNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new ResourceAuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $entity = new ResourceAuthor(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(AuthorIdentity::class),
            $this->createMock(HttpResourceIdentity::class),
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
        $repository = new ResourceAuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $entity = new ResourceAuthor(
                    $identity,
                    $this->createMock(AuthorIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
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
        $repository = new ResourceAuthorRepository(
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
        $repository = new ResourceAuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    ResourceAuthor::class,
                    new ResourceAuthor(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(AuthorIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $this->assertSame(1, $repository->count());
    }

    public function testAll()
    {
        $repository = new ResourceAuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $entity = new ResourceAuthor(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(AuthorIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(ResourceAuthor::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new ResourceAuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $entity = new ResourceAuthor(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(AuthorIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(ResourceAuthor::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }
}
