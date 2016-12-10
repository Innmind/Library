<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Citation;

use Domain\{
    Specification\Citation\Text,
    Entity\Citation\Text as Model
};
use Innmind\Specification\ComparatorInterface;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new Text(new Model('foo'));

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('text', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('foo', $spec->value());
    }
}
