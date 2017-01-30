<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Canonical;

use Domain\{
    Specification\Canonical\AndSpecification,
    Specification\Canonical\SpecificationInterface,
    Specification\AndSpecification as ParentSpec,
    Entity\Canonical,
    Entity\Canonical\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

class AndSpecificationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new AndSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new AndSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );
        $canonical = new Canonical(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(true);
        $spec
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($canonical));
        $this->assertTrue($spec->isSatisfiedBy($canonical));
    }
}
