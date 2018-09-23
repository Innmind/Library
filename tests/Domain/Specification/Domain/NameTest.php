<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\{
    Specification\Domain\Name,
    Specification\Domain\Specification,
    Specification\Domain\AndSpecification,
    Specification\Domain\OrSpecification,
    Specification\Domain\Not,
    Entity\Domain,
    Entity\Domain\Identity,
    Entity\Domain\Name as Model,
    Entity\Domain\TopLevelDomain
};
use Innmind\Specification\ComparatorInterface;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testInterface()
    {
        $spec = new Name(new Model('foo'));

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('name', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Name(new Model('foo'));
        $domain = new Domain(
            $this->createMock(Identity::class),
            new Model('foo'),
            new TopLevelDomain('fr')
        );

        $this->assertTrue($spec->isSatisfiedBy($domain));

        $domain = new Domain(
            $this->createMock(Identity::class),
            new Model('bar'),
            new TopLevelDomain('fr')
        );

        $this->assertFalse($spec->isSatisfiedBy($domain));
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
