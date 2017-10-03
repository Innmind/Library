<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Reference;

use Domain\{
    Specification\Reference\AndSpecification,
    Specification\Reference\Specification,
    Specification\AndSpecification as ParentSpec,
    Entity\Reference,
    Entity\Reference\Identity,
    Entity\HttpResource\Identity as ResourceIdentity
};
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
        $reference = new Reference(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($reference)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($reference)
            ->willReturn(true);
        $spec
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($reference)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($reference));
        $this->assertTrue($spec->isSatisfiedBy($reference));
    }
}
