<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Domain;

use Domain\Specification\Domain\Name;
use Innmind\Specification\ComparatorInterface;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new Name('foo');

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('name', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo', $spec->value());
    }
}
