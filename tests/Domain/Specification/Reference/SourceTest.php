<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Reference;

use Domain\{
    Specification\Reference\Source,
    Specification\Reference\Specification,
    Specification\Reference\AndSpecification,
    Specification\Reference\OrSpecification,
    Specification\Reference\Not,
    Entity\Reference as Entity,
    Entity\Reference\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
};
use Innmind\Specification\Comparator;
use PHPUnit\Framework\TestCase;

class SourceTest extends TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('toString')
            ->willReturn('uuid');
        $spec = new Source($identity);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('source', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('toString')
            ->willReturn('uuid');
        $spec = new Source($identity);
        $reference = new Entity(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $reference
            ->source()
            ->expects($this->at(0))
            ->method('toString')
            ->willReturn('uuid');
        $reference
            ->source()
            ->expects($this->at(1))
            ->method('toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($reference));
        $this->assertFalse($spec->isSatisfiedBy($reference));
    }

    public function testAnd()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Source($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Source($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Source($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
