<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\HtmlPageRepository,
    Entity\HtmlPage\Identity,
};
use Domain\{
    Repository\HtmlPageRepository as HtmlPageRepositoryInterface,
    Entity\HtmlPage,
    Specification\HttpResource\Specification,
    Exception\HtmlPageNotFound,
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

class HtmlPageRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            HtmlPageRepositoryInterface::class,
            new HtmlPageRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new HtmlPageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new HtmlPage(
                    $identity,
                    Path::none(),
                    Query::none()
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new HtmlPageRepository(
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

        $this->expectException(HtmlPageNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new HtmlPageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $entity = new HtmlPage(
            new Identity((string) Uuid::uuid4()),
            Path::none(),
            Query::none()
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $this->assertSame($repository, $repository->add($entity));
    }

    public function testRemove()
    {
        $repository = new HtmlPageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $entity = new HtmlPage(
                    $identity,
                    Path::none(),
                    Query::none()
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
        $repository = new HtmlPageRepository(
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
        $repository = new HtmlPageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    HtmlPage::class,
                    new HtmlPage(
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
        $repository = new HtmlPageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $entity = new HtmlPage(
                        new Identity((string) Uuid::uuid4()),
                        Path::none(),
                        Query::none()
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(HtmlPage::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new HtmlPageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $entity = new HtmlPage(
                        new Identity((string) Uuid::uuid4()),
                        Path::none(),
                        Query::none()
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(HtmlPage::class, (string) $all->type());
        $this->assertSame([$entity], unwrap($all));
    }
}
