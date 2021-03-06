<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\CitationAppearance;

use Domain\{
    Specification\CitationAppearance\Not,
    Specification\CitationAppearance\Specification,
    Specification\Not as ParentSpec,
    Entity\CitationAppearance,
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
};
use Innmind\TimeContinuum\PointInTime;
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
        $appearance = new CitationAppearance(
            $this->createMock(Identity::class),
            $this->createMock(CitationIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTime::class)
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($appearance)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($appearance));
        $this->assertFalse($spec->isSatisfiedBy($appearance));
    }
}
