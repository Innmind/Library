<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\CitationRepository,
    Entity\Citation\Identity,
};
use Domain\{
    Repository\CitationRepository as CitationRepositoryInterface,
    Entity\Citation,
    Entity\Citation\Text,
    Specification\Citation\Specification,
    Exception\CitationNotFound,
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound,
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\unwrap;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class CitationRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CitationRepositoryInterface::class,
            new CitationRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new CitationRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Citation(
                    $identity,
                    new Text('foo')
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new CitationRepository(
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

        $this->expectException(CitationNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new CitationRepository(
            $infra = $this->createMock(Repository::class)
        );
        $citation = new Citation(
            new Identity((string) Uuid::uuid4()),
            new Text('foo')
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($citation);

        $this->assertSame($repository, $repository->add($citation));
    }

    public function testRemove()
    {
        $repository = new CitationRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $citation = new Citation(
                    $identity,
                    new Text('foo')
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($citation);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new CitationRepository(
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
        $repository = new CitationRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    Citation::class,
                    new Citation(
                        new Identity((string) Uuid::uuid4()),
                        new Text('foo')
                    )
                )
            );

        $this->assertSame(1, $repository->count());
    }

    public function testAll()
    {
        $repository = new CitationRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $citation = new Citation(
                        new Identity((string) Uuid::uuid4()),
                        new Text('foo')
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Citation::class, (string) $all->type());
        $this->assertSame([$citation], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new CitationRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $citation = new Citation(
                        new Identity((string) Uuid::uuid4()),
                        new Text('foo')
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Citation::class, (string) $all->type());
        $this->assertSame([$citation], unwrap($all));
    }
}
