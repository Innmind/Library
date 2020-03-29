<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Canonical;

use Domain\{
    Specification\Canonical\Canonical,
    Specification\Canonical\Specification,
    Specification\Canonical\AndSpecification,
    Specification\Canonical\OrSpecification,
    Specification\Canonical\Not,
    Entity\Canonical as Entity,
    Entity\Canonical\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
};
use Innmind\Specification\Comparator;
use Innmind\TimeContinuum\PointInTime;
use PHPUnit\Framework\TestCase;

class CanonicalTest extends TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('toString')
            ->willReturn('uuid');
        $spec = new Canonical($identity);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('canonical', $spec->property());
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
        $spec = new Canonical($identity);
        $canonical = new Entity(
            $this->createMock(Identity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTime::class)
        );
        $canonical
            ->canonical()
            ->expects($this->at(0))
            ->method('toString')
            ->willReturn('uuid');
        $canonical
            ->canonical()
            ->expects($this->at(1))
            ->method('toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($canonical));
        $this->assertFalse($spec->isSatisfiedBy($canonical));
    }

    public function testAnd()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Canonical($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Canonical($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new Canonical($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
