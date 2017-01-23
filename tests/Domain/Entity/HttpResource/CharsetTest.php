<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity\HttpResource;

use Domain\Entity\HttpResource\Charset;

class CharsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider valid
     */
    public function testInterface(string $value)
    {
        $this->assertSame($value, (string) new Charset($value));
    }

    /**
     * @dataProvider invalid
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidString(string $value)
    {
        new Charset($value);
    }

    public function valid(): array
    {
        return [
            ['unicode-1-1'],
            ['iso-8859-5'],
            ['Shift_JIS'],
            ['ISO_8859-9:1989'],
            ['NF_Z_62-010_(1973)'],
        ];
    }

    public function invalid(): array
    {
        return [
            [''],
            ['@'],
            ['bar+suffix'],
            ['foo/bar'],
        ];
    }
}