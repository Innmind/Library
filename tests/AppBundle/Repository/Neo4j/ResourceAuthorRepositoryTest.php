<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\ResourceAuthorRepository,
    Entity\ResourceAuthor\Identity
};
use Domain\{
    Repository\ResourceAuthorRepository as ResourceAuthorRepositoryInterface,
    Entity\ResourceAuthor,
    Entity\Author\Identity as AuthorIdentity,
    Entity\HttpResource\Identity as HttpResourceIdentity,
    Specification\ResourceAuthor\Specification
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound
};
use Innmind\TimeContinuum\PointInTimeInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};
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
                    $this->createMock(PointInTimeInterface::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    /**
     * @expectedException Domain\Exception\ResourceAuthorNotFoundException
     */
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
        $repository = new ResourceAuthorRepository(
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
        $repository = new ResourceAuthorRepository(
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
        $repository = new ResourceAuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $entity = new ResourceAuthor(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(AuthorIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(ResourceAuthor::class, (string) $all->type());
        $this->assertSame([$entity], $all->toPrimitive());
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
                (new Set('object'))->add(
                    $entity = new ResourceAuthor(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(AuthorIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTimeInterface::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(ResourceAuthor::class, (string) $all->type());
        $this->assertSame([$entity], $all->toPrimitive());
    }
}
