<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Author;

use Domain\{
    Specification\Author\AndSpecification,
    Specification\Author\Specification,
    Specification\AndSpecification as ParentSpec,
    Entity\Author,
    Entity\Author\Identity,
    Entity\Author\Name,
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
        $author = new Author(
            $this->createMock(Identity::class),
            new Name('foo')
        );
        $spec
            ->left()
            ->expects($this->at(0))
            ->method('isSatisfiedBy')
            ->with($author)
            ->willReturn(false);
        $spec
            ->left()
            ->expects($this->at(1))
            ->method('isSatisfiedBy')
            ->with($author)
            ->willReturn(true);
        $spec
            ->right()
            ->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($author)
            ->willReturn(true);

        $this->assertFalse($spec->isSatisfiedBy($author));
        $this->assertTrue($spec->isSatisfiedBy($author));
    }
}
