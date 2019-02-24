<?php
declare(strict_types = 1);

namespace Tests\Domain\Model;

use Domain\{
    Model\Language,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    /**
     * @dataProvider valid
     */
    public function testInterface(string $value)
    {
        $this->assertSame($value, (string) new Language($value));
    }

    /**
     * @dataProvider invalid
     */
    public function testThrowOnInvalidValue(string $value)
    {
        $this->expectException(DomainException::class);

        new Language($value);
    }

    public function valid(): array
    {
        return [
            ['fr'],
            ['fr-FR'],
            ['f'],
            ['f-f'],
            ['f-F'],
        ];
    }

    public function invalid(): array
    {
        return [
            [''],
            ['-fr'],
            ['fr_FR'],
            ['fr_fr'],
            ['f_f'],
            ['f_F'],
        ];
    }
}
