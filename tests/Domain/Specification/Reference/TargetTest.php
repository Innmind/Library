<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Reference;

use Domain\{
    Specification\Reference\Target,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

class TargetTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Target($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('target', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }
}
