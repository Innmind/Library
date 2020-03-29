<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\CitationAppearanceRepository,
    Entity\CitationAppearance\Identity,
};
use Domain\{
    Repository\CitationAppearanceRepository as CitationAppearanceRepositoryInterface,
    Entity\CitationAppearance,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as HttpResourceIdentity,
    Specification\CitationAppearance\Specification,
    Exception\CitationAppearanceNotFound,
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

class CitationAppearanceRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CitationAppearanceRepositoryInterface::class,
            new CitationAppearanceRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new CitationAppearanceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new CitationAppearance(
                    $identity,
                    $this->createMock(CitationIdentity::class),
                    $this->createMock(HttpResourceIdentity::class),
                    $this->createMock(PointInTime::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new CitationAppearanceRepository(
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

        $this->expectException(CitationAppearanceNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new CitationAppearanceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $entity = new CitationAppearance(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(CitationIdentity::class),
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
        $repository = new CitationAppearanceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $entity = new CitationAppearance(
                    $identity,
                    $this->createMock(CitationIdentity::class),
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
        $repository = new CitationAppearanceRepository(
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
        $repository = new CitationAppearanceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    CitationAppearance::class,
                    new CitationAppearance(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(CitationIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $this->assertSame(1, $repository->count());
    }

    public function testAll()
    {
        $repository = new CitationAppearanceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $entity = new CitationAppearance(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(CitationIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(CitationAppearance::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new CitationAppearanceRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $entity = new CitationAppearance(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(CitationIdentity::class),
                        $this->createMock(HttpResourceIdentity::class),
                        $this->createMock(PointInTime::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(CitationAppearance::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }
}
