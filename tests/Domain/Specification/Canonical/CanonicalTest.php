<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Canonical;

use Domain\{
    Specification\Canonical\Canonical,
    Entity\HttpResource\IdentityInterface
};
use Innmind\Specification\ComparatorInterface;

class CanonicalTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('uuid');
        $spec = new Canonical($identity);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('canonical', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('uuid', $spec->value());
    }
}
