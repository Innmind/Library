<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HostResource;

use Domain\{
    Specification\HostResource\OrSpecification,
    Specification\HostResource\SpecificationInterface,
    Specification\OrSpecification as ParentSpec,
    Entity\HostResource,
    Entity\HostResource\IdentityInterface,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

class OrSpecificationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new OrSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new OrSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );
        $relation = new HostResource(
            $this->createMock(IdentityInterface::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(2))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(3))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($relation));
        $this->assertTrue($spec->isSatisfiedBy($relation));
        $this->assertTrue($spec->isSatisfiedBy($relation));
        $this->assertTrue($spec->isSatisfiedBy($relation));
    }
}
