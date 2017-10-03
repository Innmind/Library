<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Reference;

use Domain\{
    Specification\Reference\Not,
    Specification\Reference\Specification,
    Specification\Not as ParentSpec,
    Entity\Reference,
    Entity\Reference\Identity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use PHPUnit\Framework\TestCase;

class NotTest extends TestCase
{
    public function testInterface()
    {
        $spec = new Not(
            $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new Not(
            $this->createMock(Specification::class)
        );
        $reference = new Reference(
            $this->createMock(Identity::class),
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
