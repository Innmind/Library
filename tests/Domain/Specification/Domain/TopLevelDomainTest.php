<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\{
    Specification\Domain\TopLevelDomain,
    Specification\Domain\Specification,
    Specification\Domain\AndSpecification,
    Specification\Domain\OrSpecification,
    Specification\Domain\Not,
    Entity\Domain,
    Entity\Domain\Identity,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain as Model,
};
use Innmind\Specification\Comparator;
use PHPUnit\Framework\TestCase;

class TopLevelDomainTest extends TestCase
{
    public function testInterface()
    {
        $spec = new TopLevelDomain(new Model('foo'));

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('tld', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('foo', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new TopLevelDomain(new Model('fr'));
        $domain = new Domain(
            $this->createMock(Identity::class),
            new Name('foo'),
            new Model('fr')
        );

        $this->assertTrue($spec->isSatisfiedBy($domain));

        $domain = new Domain(
            $this->createMock(Identity::class),
            new Name('foo'),
            new Model('en')
        );

        $this->assertFalse($spec->isSatisfiedBy($domain));
    }

    public function testAnd()
    {
        $spec = new TopLevelDomain(new Model('foo'));

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $spec = new TopLevelDomain(new Model('foo'));

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $spec = new TopLevelDomain(new Model('foo'));

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
