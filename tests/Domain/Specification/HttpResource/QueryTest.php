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
    Query as QueryModel,
    Path,
};
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function testInterface()
    {
        $query = QueryModel::of('?foo');
        $spec = new Query($query);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('query', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('?foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Query(QueryModel::of('foo'));

        $this->assertTrue($spec->isSatisfiedBy(new HttpResource(
            $this->createMock(Identity::class),
            Path::none(),
            QueryModel::of('foo')
        )));
        $this->assertFalse($spec->isSatisfiedBy(new HttpResource(
            $this->createMock(Identity::class),
            Path::none(),
            QueryModel::of('bar')
        )));
    }

    public function testAnd()
    {
        $query = QueryModel::none();
        $spec = new Query($query);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $query = QueryModel::none();
        $spec = new Query($query);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $path = QueryModel::none();
        $spec = new Query($path);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
