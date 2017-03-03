<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Citation;

use Domain\{
    Specification\Citation\Not,
    Specification\Citation\SpecificationInterface,
    Specification\Not as ParentSpec,
    Entity\Citation,
    Entity\Citation\IdentityInterface,
    Entity\Citation\Text
};
use PHPUnit\Framework\TestCase;

class NotTest extends TestCase
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
        $citation = new Citation(
            $this->createMock(IdentityInterface::class),
            new Text('foo')
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($citation)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($citation)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($citation));
        $this->assertFalse($spec->isSatisfiedBy($citation));
    }
}
