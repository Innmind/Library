<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Host;

use Domain\{
    Specification\Host\Not,
    Specification\Host\SpecificationInterface,
    Specification\Not as ParentSpec,
    Entity\Host,
    Entity\Host\IdentityInterface,
    Entity\Host\Name
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
        $host = new Host(
            $this->createMock(IdentityInterface::class),
            new Name('foo')
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($host));
        $this->assertFalse($spec->isSatisfiedBy($host));
    }
}
