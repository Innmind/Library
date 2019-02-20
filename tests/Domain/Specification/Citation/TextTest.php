<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Citation;

use Domain\{
    Specification\Citation\Text,
    Specification\Citation\Specification,
    Specification\Citation\AndSpecification,
    Specification\Citation\OrSpecification,
    Specification\Citation\Not,
    Entity\Citation,
    Entity\Citation\Identity,
    Entity\Citation\Text as Model,
};
use Innmind\Specification\Comparator;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testInterface()
    {
        $spec = new Text(new Model('foo'));

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('text', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Text(new Model('foo'));
        $citation = new Citation(
            $this->createMock(Identity::class),
            new Model('foo')
        );

        $this->assertTrue($spec->isSatisfiedBy($citation));

        $citation = new Citation(
            $this->createMock(Identity::class),
            new Model('bar')
        );

        $this->assertFalse($spec->isSatisfiedBy($citation));
    }

    public function testAnd()
    {
        $spec = new Text(new Model('foo'));

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $spec = new Text(new Model('foo'));

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $spec = new Text(new Model('foo'));

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
