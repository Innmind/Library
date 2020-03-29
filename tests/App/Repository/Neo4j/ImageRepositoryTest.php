<?php
declare(strict_types = 1);

namespace Tests\App\Repository\Neo4j;

use App\{
    Repository\Neo4j\ImageRepository,
    Entity\Image\Identity,
};
use Domain\{
    Repository\ImageRepository as ImageRepositoryInterface,
    Entity\Image,
    Specification\HttpResource\Specification,
    Exception\ImageNotFound,
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

class ImageRepositoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ImageRepositoryInterface::class,
            new ImageRepository(
                $this->createMock(Repository::class)
            )
        );
    }

    public function testGet()
    {
        $repository = new ImageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $expected = new Image(
                    $identity,
                    Path::none(),
                    Query::none()
                )
            );

        $this->assertSame($expected, $repository->get($identity));
    }

    public function testThrowWhenGettingUnknownEntity()
    {
        $repository = new ImageRepository(
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

        $this->expectException(ImageNotFound::class);
        $this->expectExceptionMessage('');
        $this->expectExceptionCode(0);

        $repository->get($identity);
    }

    public function testAdd()
    {
        $repository = new ImageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $image = new Image(
            new Identity((string) Uuid::uuid4()),
            Path::none(),
            Query::none()
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
            $infra = $this->createMock(Repository::class)
        );
        $identity = new Identity((string) Uuid::uuid4());
        $infra
            ->expects($this->once())
            ->method('get')
            ->with($identity)
            ->willReturn(
                $image = new Image(
                    $identity,
                    Path::none(),
                    Query::none()
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
        $repository = new ImageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::of(
                    Image::class,
                    new Image(
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
        $repository = new ImageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $infra
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                Set::objects(
                    $image = new Image(
                        new Identity((string) Uuid::uuid4()),
                        Path::none(),
                        Query::none()
                    )
                )
            );

        $all = $repository->all();

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Image::class, (string) $all->type());
        $this->assertSame([$image], unwrap($all));
    }

    public function testMatching()
    {
        $repository = new ImageRepository(
            $infra = $this->createMock(Repository::class)
        );
        $specification = $this->createMock(Specification::class);
        $infra
            ->expects($this->once())
            ->method('matching')
            ->with($specification)
            ->willReturn(
                Set::objects(
                    $image = new Image(
                        new Identity((string) Uuid::uuid4()),
                        Path::none(),
                        Query::none()
                    )
                )
            );

        $all = $repository->matching($specification);

        $this->assertInstanceOf(Set::class, $all);
        $this->assertSame(Image::class, (string) $all->type());
        $this->assertSame([$image], unwrap($all));
    }
}
