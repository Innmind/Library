<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Author;

use Domain\{
    Specification\Author\Name,
    Specification\Author\SpecificationInterface,
    Specification\Author\AndSpecification,
    Specification\Author\OrSpecification,
    Specification\Author\Not,
    Entity\Author\Name as Model,
    Entity\Author,
    Entity\Author\IdentityInterface
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
        $author = new Author(
            $this->createMock(IdentityInterface::class),
            new Model('foo')
        );

        $this->assertTrue($spec->isSatisfiedBy($author));

        $author = new Author(
            $this->createMock(IdentityInterface::class),
            new Model('bar')
        );

        $this->assertFalse($spec->isSatisfiedBy($author));
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
