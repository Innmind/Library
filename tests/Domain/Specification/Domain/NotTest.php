<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\{
    Specification\Domain\Not,
    Specification\Domain\Specification,
    Specification\Not as ParentSpec,
    Entity\Domain,
    Entity\Domain\Identity,
    Entity\Domain\Name,
    Entity\Domain\TopLevelDomain
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
        $domain = new Domain(
            $this->createMock(Identity::class),
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
