<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\Alternate,
    Specification\Alternate\SpecificationInterface,
    Specification\Alternate\AndSpecification,
    Specification\Alternate\OrSpecification,
    Specification\Alternate\Not,
    Entity\Alternate\IdentityInterface,
    Entity\Alternate as Entity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Model\Language
};
use Innmind\Specification\ComparatorInterface;

class AlternateTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Alternate($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('alternate', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Alternate($identity);
        $alternate = new Entity(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Language('fr')
        );
        $alternate
            ->alternate()
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('uuid');
        $alternate
            ->alternate()
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($alternate));
        $this->assertFalse($spec->isSatisfiedBy($alternate));
    }

    public function testAnd()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Alternate($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Alternate($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Alternate($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
