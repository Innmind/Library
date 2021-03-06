<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\AndSpecification,
    Specification\Alternate\Specification,
    Specification\AndSpecification as ParentSpec,
    Entity\Alternate,
    Entity\Alternate\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Model\Language,
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
        $alternate = new Alternate(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Language('fr')
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($alternate)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($alternate)
            ->willReturn(true);
        $spec
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($alternate)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($alternate));
        $this->assertTrue($spec->isSatisfiedBy($alternate));
    }
}
