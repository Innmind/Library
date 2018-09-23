<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Author;

use Domain\{
    Specification\Author\Not,
    Specification\Author\Specification,
    Specification\Not as ParentSpec,
    Entity\Author,
    Entity\Author\Identity,
    Entity\Author\Name
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
        $author = new Author(
            $this->createMock(Identity::class),
            new Name('foo')
        );
        $spec
            ->specification()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($author)
            ->willReturn(false);
        $spec
            ->specification()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($author)
            ->willReturn(true);

        $this->assertTrue($spec->isSatisfiedBy($author));
        $this->assertFalse($spec->isSatisfiedBy($author));
    }
}
