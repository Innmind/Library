<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\{
    Specification\HttpResource\Query,
    Specification\HttpResource\Specification,
    Specification\HttpResource\AndSpecification,
    Specification\HttpResource\OrSpecification,
    Specification\HttpResource\Not,
    Entity\HttpResource,
    Entity\HttpResource\Identity,
};
use Innmind\Specification\Comparator;
use Innmind\Url\{
    QueryInterface,
    PathInterface,
};
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function testInterface()
    {
        $query = $this->createMock(QueryInterface::class);
        $query
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?foo');
        $spec = new Query($query);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('query', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('?foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $query = $this->createMock(QueryInterface::class);
        $query
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?foo');
        $spec = new Query($query);
        $resource = new HttpResource(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );
        $query
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('?foo');
        $query
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('?bar');

        $this->assertTrue($spec->isSatisfiedBy($resource));
        $this->assertFalse($spec->isSatisfiedBy($resource));
    }

    public function testAnd()
    {
        $query = $this->createMock(QueryInterface::class);
        $spec = new Query($query);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $query = $this->createMock(QueryInterface::class);
        $spec = new Query($query);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $path = $this->createMock(QueryInterface::class);
        $spec = new Query($path);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
