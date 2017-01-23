<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\{
    Specification\Domain\Not,
    Specification\Domain\SpecificationInterface,
    Specification\Not as ParentSpec,
    Entity\Domain,
    Entity\Domain\IdentityInterface,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain
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
        $domain = new Domain(
            $this->createMock(IdentityInterface::class),
            new Name('foo'),
            new TopLevelDomain('fr')
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($domain));
        $this->assertFalse($spec->isSatisfiedBy($domain));
    }
}
