<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\DomainRepository,
    Entity\Domain\Identity
};
use Domain\{
    Repository\DomainRepositoryInterface,
    Entity\Domain,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain,
    Specification\Domain\SpecificationInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};
use Ramsey\Uuid\Uuid;

class DomainRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            DomainRepositoryInterface::class,
            new DomainRepository(
                $this->createMock(RepositoryInterface::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new DomainRepository(
            $infra = $this->createMock(RepositoryInterface::class)
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

    public function testAdd()
    {
        $repository = new DomainRepository(
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
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
        $repository = new DomainRepository(
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
        $repository = new DomainRepository(
            $infra = $this->createMock(RepositoryInterface::class)
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
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $specification = $this->createMock(SpecificationInterface::class);
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