<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\CitationAppearance;

use Domain\{
    Specification\CitationAppearance\HttpResource,
    Specification\CitationAppearance\Specification,
    Specification\CitationAppearance\AndSpecification,
    Specification\CitationAppearance\OrSpecification,
    Specification\CitationAppearance\Not,
    Entity\CitationAppearance as Entity,
    Entity\CitationAppearance\Identity,
    Entity\Citation\Identity as CitationIdentity,
    Entity\HttpResource\Identity as ResourceIdentity,
};
use Innmind\Specification\Comparator;
use Innmind\TimeContinuum\PointInTimeInterface;
use PHPUnit\Framework\TestCase;

class HttpResourceTest extends TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(Comparator::class, $spec);
        $this->assertInstanceOf(Specification::class, $spec);
        $this->assertSame('resource', $spec->property());
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
        $spec = new HttpResource($identity);
        $appearance = new Entity(
            $this->createMock(Identity::class),
            $this->createMock(CitationIdentity::class),
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PointInTimeInterface::class)
        );
        $appearance
            ->resource()
            ->expects($this->at(0))
            ->method('__toString')
            ->willReturn('uuid');
        $appearance
            ->resource()
            ->expects($this->at(1))
            ->method('__toString')
            ->willReturn('foo');

        $this->assertTrue($spec->isSatisfiedBy($appearance));
        $this->assertFalse($spec->isSatisfiedBy($appearance));
    }

    public function testAnd()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(
            AndSpecification::class,
            $spec->and($spec)
        );
    }

    public function testOr()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(
            OrSpecification::class,
            $spec->or($spec)
        );
    }

    public function testNot()
    {
        $identity = $this->createMock(ResourceIdentity::class);
        $spec = new HttpResource($identity);

        $this->assertInstanceOf(
            Not::class,
            $spec->not()
        );
    }
}
