<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\CitationAppearance;

use Domain\{
    Specification\CitationAppearance\OrSpecification,
    Specification\CitationAppearance\Specification,
    Specification\OrSpecification as ParentSpec,
    Entity\CitationAppearance,
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
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
        $appearance = new CitationAppearance(
            $this->createMock(Identity::class),
            $this->createMock(CitationIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(2))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(3))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($appearance));
        $this->assertTrue($spec->isSatisfiedBy($appearance));
        $this->assertTrue($spec->isSatisfiedBy($appearance));
        $this->assertTrue($spec->isSatisfiedBy($appearance));
    }
}
