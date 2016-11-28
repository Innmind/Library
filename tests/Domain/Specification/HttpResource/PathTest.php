<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\HttpResource;

use Domain\Specification\HttpResource\Path;
use Innmind\Specification\ComparatorInterface;
use Innmind\Url\PathInterface;

class PathTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $path = $this->createMock(PathInterface::class);
        $path
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/foo');
        $spec = new Path($path);

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('path', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('/foo', $spec->value());
    }
}
