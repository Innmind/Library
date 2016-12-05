<?php
declare(strict_types = 1);

namespace Tests\Domain\Specification\Alternate;

use Domain\Specification\Alternate\Language;
use Innmind\Specification\ComparatorInterface;

class LanguageTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $spec = new Language('fr');

        $this->assertInstanceOf(ComparatorInterface::class, $spec);
        $this->assertSame('language', $spec->property());
        $this->assertSame('=', $spec->sign());
        $this->assertSame('fr', $spec->value());
    }
}
