<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\{
    Specification\HttpResource\Path,
    Specification\HttpResource\SpecificationInterface,
    Specification\HttpResource\AndSpecification,
    Specification\HttpResource\OrSpecification,
    Specification\HttpResource\Not,
    Entity\HttpResource,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;
use Innmind\Url\{
    PathInterface,
    QueryInterface
};

class PathTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $path = $this->createMock(PathInterface::class);
        $path
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/foo');
        $spec = new Path($path);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('path', $spec->property());
        $this->assertSame('=', $spec->sign());
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
            $this->createMock(IdentityInterface::class),
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
