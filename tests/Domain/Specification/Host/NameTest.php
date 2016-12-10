<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Host;

use Domain\{
    Specification\Host\Name,
    Entity\Host\Name as Model
};
use Innmind\Specification\ComparatorInterface;

class NameTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new Name(new Model('foo'));

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('name', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo', $spec->value());
    }
}
