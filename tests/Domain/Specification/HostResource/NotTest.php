<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HostResource;

use Domain\{
    Specification\HostResource\Not,
    Specification\HostResource\SpecificationInterface,
    Specification\Not as ParentSpec,
    Entity\HostResource,
    Entity\HostResource\IdentityInterface,
    Entity\Host\IdentityInterface as HostIdentity,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;

class NotTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new Not(
            $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Not(
            $this->createMock(SpecificationInterface::class)
        );
        $relation = new HostResource(
            $this->createMock(IdentityInterface::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($relation)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($relation));
        $this->assertFalse($spec->isSatisfiedBy($relation));
    }
}
