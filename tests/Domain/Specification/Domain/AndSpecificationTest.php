<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\{
    Specification\Domain\AndSpecification,
    Specification\Domain\SpecificationInterface,
    Specification\AndSpecification as ParentSpec,
    Entity\Domain,
    Entity\Domain\IdentityInterface,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain
};

class AndSpecificationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new AndSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );

        $this->assertInstanceOf(ParentSpec::class, $spec);
        $this->assertInstanceOf(SpecificationInterface::class, $spec);
    }

    public function testIsSatisfiedBy()
    {
        $spec = new AndSpecification(
            $this->createMock(SpecificationInterface::class),
            $this->createMock(SpecificationInterface::class)
        );
        $domain = new Domain(
            $this->createMock(IdentityInterface::class),
            new Name('foo'),
            new TopLevelDomain('fr')
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(true);
        $spec
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($domain));
        $this->assertTrue($spec->isSatisfiedBy($domain));
    }
}
