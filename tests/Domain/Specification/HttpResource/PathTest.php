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
    PathInterface,
    QueryInterface,
};
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testInterface()
    {
        $path = $this->createMock(PathInterface::class);
        $path
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/foo');
        $spec = new Path($path);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('path', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('/foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $path = $this->createMock(PathInterface::class);
        $path
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/foo');
        $spec = new Path($path);
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $path
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('/foo');
        $path
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('/bar');

        $this->assertTrue($spec->isSatisfiedBy($resource));
        $this->assertFalse($spec->isSatisfiedBy($resource));
    }

    public function testAnd()
    {
        $path = $this->createMock(PathInterface::class);
        $spec = new Path($path);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $path = $this->createMock(PathInterface::class);
        $spec = new Path($path);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $path = $this->createMock(PathInterface::class);
        $spec = new Path($path);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
