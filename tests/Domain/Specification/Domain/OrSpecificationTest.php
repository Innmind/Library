<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\{
    Specification\Domain\OrSpecification,
    Specification\Domain\Specification,
    Specification\OrSpecification as ParentSpec,
    Entity\Domain,
    Entity\Domain\Identity,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain,
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
        $domain = new Domain(
            $this->createMock(Identity::class),
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
            ->left()
            ->expects($this->at(2))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(true);
        $spec
            ->left()
            ->expects($this->at(3))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(false);
        $spec
            ->right()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($domain)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($domain));
        $this->assertTrue($spec->isSatisfiedBy($domain));
        $this->assertTrue($spec->isSatisfiedBy($domain));
        $this->assertTrue($spec->isSatisfiedBy($domain));
    }
}
