<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\DomainRepository,
    Entity\Domain\Identity,
};
use Domain\{
    Repository\DomainRepository as DomainRepositoryInterface,
    Entity\Domain,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain,
    Specification\Domain\Specification,
    Exception\DomainNotFound,
};
use Innmind\Neo4j\ONM\{
    Repository,
    Exception\EntityNotFound,
};
use Innmind\Immutable\{
    SetInterface,
    Set,
};
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class DomainRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            DomainRepositoryInterface::class,
            new DomainRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new DomainRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Domain(
                    $identity,
                    new Name('foo'),
                    new TopLevelDomain('com')
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new DomainRepository(
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

        $this->expectException(DomainNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new DomainRepository(
            $infra = $this->createMock(Repository::class)
        );
        $domain = new Domain(
            new Identity((string) Uuid::uuid4()),
            new Name('foo'),
            new TopLevelDomain('com')
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($domain);

        $this->assertSame($repository, $repository->add($domain));
    }

    public function testRemove()
    {
        $repository = new DomainRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $domain = new Domain(
                    $identity,
                    new Name('foo'),
                    new TopLevelDomain('com')
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($domain);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new DomainRepository(
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
        $repository = new DomainRepository(
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
        $repository = new DomainRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $domain = new Domain(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo'),
                        new TopLevelDomain('com')
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Domain::class, (string) $all->type());
        $this->assertSame([$domain], $all->toPrimitive());
    }

    public function testMatching()
    {
        $repository = new DomainRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                (new Set('object'))->add(
                    $domain = new Domain(
                        new Identity((string) Uuid::uuid4()),
                        new Name('foo'),
                        new TopLevelDomain('com')
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Domain::class, (string) $all->type());
        $this->assertSame([$domain], $all->toPrimitive());
    }
}
