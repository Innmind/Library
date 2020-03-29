<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\{
    Specification\HttpResource\Path,
    Specification\HttpResource\Specification,
    Specification\HttpResource\AndSpecification,
    Specification\HttpResource\OrSpecification,
    Specification\HttpResource\Not,
    Entity\HttpResource,
    Entity\HttpResource\Identity,
};
use Innmind\Specification\Comparator;
use Innmind\Url\{
    Path as PathModel,
    Query,
};
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testInterface()
    {
        $path = PathModel::of('/foo');
        $spec = new Path($path);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('path', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('/foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Path(PathModel::of('/foo'));

        $this->assertTrue($spec->isSatisfiedBy(new HttpResource(
            $this->createMock(Identity::class),
            PathModel::of('/foo'),
            Query::none()
        )));
        $this->assertFalse($spec->isSatisfiedBy(new HttpResource(
            $this->createMock(Identity::class),
            PathModel::of('/bar'),
            Query::none()
        )));
    }

    public function testAnd()
    {
        $path = PathModel::none();
        $spec = new Path($path);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $path = PathModel::none();
        $spec = new Path($path);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $path = PathModel::none();
        $spec = new Path($path);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
