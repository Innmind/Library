<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Reference;

use Domain\{
    Specification\Reference\Target,
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

class TargetTest extends TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Target($identity);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('target', $spec->property());
        $this->assertSame('=', (string) $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }

    public function testIsSatisfiedBy()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Target($identity);
        $reference = new Entity(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class)
        );
        $reference
            ->target()
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('uuid');
        $reference
            ->target()
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($reference));
        $this->assertFalse($spec->isSatisfiedBy($reference));
    }

    public function testAnd()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Target($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Target($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Target($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
