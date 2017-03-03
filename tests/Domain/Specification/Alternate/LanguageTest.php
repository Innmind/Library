<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\Language,
    Specification\Alternate\SpecificationInterface,
    Specification\Alternate\AndSpecification,
    Specification\Alternate\OrSpecification,
    Specification\Alternate\Not,
    Entity\Alternate,
    Entity\Alternate\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Model\Language as Model
};
use Innmind\Specification\ComparatorInterface;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    public function testInterface()
    {
        $spec = new Language(new Model('fr'));

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('language', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('fr', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Language(new Model('fr'));
        $alternate = new Alternate(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Model('fr')
        );

        $this->assertTrue($spec->isSatisfiedBy($alternate));

        $alternate = new Alternate(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Model('en')
        );
        $this->assertFalse($spec->isSatisfiedBy($alternate));
    }

    public function testAnd()
    {
        $spec = new Language(new Model('fr'));

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $spec = new Language(new Model('fr'));

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $spec = new Language(new Model('fr'));

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
