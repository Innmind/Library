<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\Not,
    Specification\Alternate\Specification,
    Specification\Not as ParentSpec,
    Entity\Alternate,
    Entity\Alternate\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Model\Language,
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
        $alternate = new Alternate(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            new Language('fr')
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($alternate)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($alternate)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($alternate));
        $this->assertFalse($spec->isSatisfiedBy($alternate));
    }
}
