<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Host;

use Domain\{
    Specification\Host\Name,
    Specification\Host\SpecificationInterface,
    Specification\Host\AndSpecification,
    Specification\Host\OrSpecification,
    Specification\Host\Not,
    Entity\Host,
    Entity\Host\IdentityInterface,
    Entity\Host\Name as Model
};
use Innmind\Specification\ComparatorInterface;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new Name(new Model('foo'));

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('name', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Name(new Model('foo'));
        $host = new Host(
            $this->createMock(IdentityInterface::class),
            new Model('foo')
        );

        $this->assertTrue($spec->isSatisfiedBy($host));

        $host = new Host(
            $this->createMock(IdentityInterface::class),
            new Model('bar')
        );

        $this->assertFalse($spec->isSatisfiedBy($host));
    }

    public function testAnd()
    {
        $spec = new Name(new Model('foo'));

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $spec = new Name(new Model('foo'));

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $spec = new Name(new Model('foo'));

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
