<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Citation;

use Domain\{
    Specification\Citation\AndSpecification,
    Specification\Citation\SpecificationInterface,
    Specification\AndSpecification as ParentSpec,
    Entity\Citation,
    Entity\Citation\IdentityInterface,
    Entity\Citation\Text
};

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
        $citation = new Citation(
            $this->createMock(IdentityInterface::class),
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
