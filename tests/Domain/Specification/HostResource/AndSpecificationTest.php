<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HostResource;

use Domain\{
    Specification\HostResource\AndSpecification,
    Specification\HostResource\Specification,
    Specification\AndSpecification as ParentSpec,
    Entity\HostResource,
    Entity\HostResource\Identity,
    Entity\Host\Identity as HostIdentity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class AndSpecificationTest extends TestCase
{
    public function testInterface()
    {
        $spec = new AndSpecification(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new AndSpecification(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );
        $relation = new HostResource(
            $this->createMock(Identity::class),
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
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($relation));
        $this->assertTrue($spec->isSatisfiedBy($relation));
    }
}
