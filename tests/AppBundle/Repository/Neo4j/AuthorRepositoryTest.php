<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\AuthorRepository,
    Entity\Author\Identity
};
use Domain\{
    Repository\AuthorRepositoryInterface,
    Entity\Author,
    Entity\Author\Name,
    Specification\Author\SpecificationInterface
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

class AuthorRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            AuthorRepositoryInterface::class,
            new AuthorRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new AuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Author(
                    $identity,
                    new Name('foo')
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    /**
     * @expectedException Domain\Exception\AuthorNotFoundException
     */
    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new AuthorRepository(
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
        $repository = new AuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $author = new Author(
            new Identity((string) Uuid::uuid4()),
            new Name('foo')
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($author);

        $this->assertSame($repository, $repository->add($author));
    }

    public function testRemove()
    {
        $repository = new AuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $author = new Author(
                    $identity,
                    new Name('foo')
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($author);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new AuthorRepository(
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
        $repository = new AuthorRepository(
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
        $repository = new AuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $author = new Author(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo')
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Author::class, (string) $all->type());
        $this->assertSame([$author], $all->toPrimitive());
    }

    public function testMatching()
    {
        $repository = new AuthorRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(SpecificationInterface::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                (new Set('object'))->add(
                    $author = new Author(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo')
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Author::class, (string) $all->type());
        $this->assertSame([$author], $all->toPrimitive());
    }
}
