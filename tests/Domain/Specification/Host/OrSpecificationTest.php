<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Host;

use Domain\{
    Specification\Host\OrSpecification,
    Specification\Host\Specification,
    Specification\OrSpecification as ParentSpec,
    Entity\Host,
    Entity\Host\Identity,
    Entity\Host\Name,
};
use PHPUnit\Framework\TestCase;

class OrSpecificationTest extends TestCase
{
    public function testInterface()
    {
        $spec = new OrSpecification(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new OrSpecification(
            $this->createMock(Specification::class),
            $this->createMock(Specification::class)
        );
        $host = new Host(
            $this->createMock(Identity::class),
            new Name('foo')
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(2))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(3))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($host)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($host));
        $this->assertTrue($spec->isSatisfiedBy($host));
        $this->assertTrue($spec->isSatisfiedBy($host));
        $this->assertTrue($spec->isSatisfiedBy($host));
    }
}
