<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Author;

use Domain\{
    Specification\Author\Name,
    Entity\Author\Name as Model
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
