<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\CitationAppearance;

use Domain\{
    Specification\CitationAppearance\Citation,
    Specification\CitationAppearance\SpecificationInterface,
    Specification\CitationAppearance\AndSpecification,
    Specification\CitationAppearance\OrSpecification,
    Specification\CitationAppearance\Not,
    Entity\CitationAppearance as Entity,
    Entity\CitationAppearance\IdentityInterface,
    Entity\Citation\IdentityInterface as CitationIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\Specification\ComparatorInterface;
use Innmind\TimeContinuum\PointInTimeInterface;

class CitationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(CitationIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Citation($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
        $this->assertSame('citation', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $identity = $this->createMock(CitationIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Citation($identity);
        $appearance = new Entity(
            $this->createMock(IdentityInterface::class),
            $this->createMock(CitationIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $appearance
            ->citation()
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('uuid');
        $appearance
            ->citation()
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($appearance));
        $this->assertFalse($spec->isSatisfiedBy($appearance));
    }

    public function testAnd()
    {
        $identity = $this->createMock(CitationIdentity::class);
        $spec = new Citation($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(CitationIdentity::class);
        $spec = new Citation($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(CitationIdentity::class);
        $spec = new Citation($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
