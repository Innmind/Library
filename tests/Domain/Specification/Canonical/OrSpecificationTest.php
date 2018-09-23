<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Canonical;

use Domain\{
    Specification\Canonical\OrSpecification,
    Specification\Canonical\Specification,
    Specification\OrSpecification as ParentSpec,
    Entity\Canonical,
    Entity\Canonical\Identity,
    Entity\HttpResource\Identity as ResourceIdentity
};
use Innmind\TimeContinuum\PointInTimeInterface;
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
        $canonical = new Canonical(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(2))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(3))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($canonical)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($canonical));
        $this->assertTrue($spec->isSatisfiedBy($canonical));
        $this->assertTrue($spec->isSatisfiedBy($canonical));
        $this->assertTrue($spec->isSatisfiedBy($canonical));
    }
}
