<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\Language,
    Specification\Alternate\Specification,
    Specification\Alternate\AndSpecification,
    Specification\Alternate\OrSpecification,
    Specification\Alternate\Not,
    Entity\Alternate,
    Entity\Alternate\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Model\Language as Model,
};
use Innmind\Specification\Comparator;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    public function testInterface()
    {
        $spec = new Language(new Model('fr'));

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('language', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('fr', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Language(new Model('fr'));
        $alternate = new Alternate(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Model('fr')
        );

        $this->assertTrue($spec->isSatisfiedBy($alternate));

        $alternate = new Alternate(
            $this->createMock(Identity::class),
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
