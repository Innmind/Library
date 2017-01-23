<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\{
    Specification\Alternate\Not,
    Specification\Alternate\SpecificationInterface,
    Specification\Not as ParentSpec,
    Entity\Alternate,
    Entity\Alternate\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Model\Language
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
        $alternate = new Alternate(
            $this->createMock(IdentityInterface::class),
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
