<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Repository\Neo4j;

use AppBundle\{
    Repository\Neo4j\ImageRepository,
    Entity\Image\Identity
};
use Domain\{
    Repository\ImageRepositoryInterface,
    Entity\Image,
    Specification\HttpResource\SpecificationInterface
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Neo4j\ONM\RepositoryInterface;
use Innmind\Immutable\{
    SetInterface,
    Set
};
use Ramsey\Uuid\Uuid;

class ImageRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ImageRepositoryInterface::class,
            new ImageRepository(
                $this->createMock(RepositoryInterface::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new ImageRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Image(
                    $identity,
                    $this->createMock(PathInterface::class),
                    $this->createMock(QueryInterface::class)
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testAdd()
    {
        $repository = new ImageRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $image = new Image(
            new Identity((string) Uuid::uuid4()),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $infra
            ->expects($this->once())
            ->method('add')
            ->with($image);

        $this->assertSame($repository, $repository->add($image));
    }

    public function testRemove()
    {
        $repository = new ImageRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $image = new Image(
                    $identity,
                    $this->createMock(PathInterface::class),
                    $this->createMock(QueryInterface::class)
                )
            );
        $infra
            ->expects($this->once())
            ->method('remove')
            ->with($image);

        $this->assertSame($repository, $repository->remove($identity));
    }

    public function testHas()
    {
        $repository = new ImageRepository(
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
        $repository = new ImageRepository(
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
        $repository = new ImageRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                (new Set('object'))->add(
                    $image = new Image(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Image::class, (string) $all->type());
        $this->assertSame([$image], $all->toPrimitive());
    }

    public function testMatching()
    {
        $repository = new ImageRepository(
            $infra = $this->createMock(RepositoryInterface::class)
        );
        $specification = $this->createMock(SpecificationInterface::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                (new Set('object'))->add(
                    $image = new Image(
                        new Identity((string) Uuid::uuid4()),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(SetInterface::class, $all);
        $this->assertSame(Image::class, (string) $all->type());
        $this->assertSame([$image], $all->toPrimitive());
    }
}
