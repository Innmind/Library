<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Citation;

use Domain\{
    Specification\Citation\AndSpecification,
    Specification\Citation\Specification,
    Specification\AndSpecification as ParentSpec,
    Entity\Citation,
    Entity\Citation\Identity,
    Entity\Citation\Text,
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
        $citation = new Citation(
            $this->createMock(Identity::class),
            new Text('foo')
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($citation)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($citation)
            ->willReturn(true);
        $spec
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($citation)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($citation));
        $this->assertTrue($spec->isSatisfiedBy($citation));
    }
}
