<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\AlternateRepository,
    Entity\Alternate\Identity,
};
use Domain\{
    Repository\AlternateRepository as AlternateRepositoryInterface,
    Entity\Alternate,
    Entity\HttpResource\Identity as HttpResourceIdentity,
    Specification\Alternate\Specification,
    Model\Language,
    Exception\AlternateNotFound,
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound,
};
use Innmind\Immutable\Set;
use function Innmind\Immutable\unwrap;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class AlternateRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            AlternateRepositoryInterface::class,
            new AlternateRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new AlternateRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Alternate(
                    $identity,
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    new Language('fr')
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new AlternateRepository(
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

        $this->expectException(AlternateNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new AlternateRepository(
            $infra = $this->createMock(Repository::class)
        );
        $entity = new Alternate(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(HttpResourceIdentity::class),
            $this->createMock(HttpResourceIdentity::class),
            new Language('fr')
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $this->assertSame($repository, $repository->add($entity));
    }

    public function testRemove()
    {
        $repository = new AlternateRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $entity = new Alternate(
                    $identity,
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    new Language('fr')
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
        $repository = new AlternateRepository(
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
        $repository = new AlternateRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    Alternate::class,
                    new Alternate(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        new Language('fr')
                    )
                )
            );

        $this->assertSame(1, $repository->count());
    }

    public function testAll()
    {
        $repository = new AlternateRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $entity = new Alternate(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        new Language('fr')
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Alternate::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new AlternateRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $entity = new Alternate(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        new Language('fr')
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Alternate::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }
}
