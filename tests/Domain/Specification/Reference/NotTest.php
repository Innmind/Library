<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Reference;

use Domain\{
    Specification\Reference\Not,
    Specification\Reference\SpecificationInterface,
    Specification\Not as ParentSpec,
    Entity\Reference,
    Entity\Reference\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity
};

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
        $reference = new Reference(
            $this->createMock(IdentityInterface::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($reference)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($reference)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($reference));
        $this->assertFalse($spec->isSatisfiedBy($reference));
    }
}
