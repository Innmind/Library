<?php
declare(strict_types = 1);

namespace Tests\Web\Factory;

use Web\Factory\DomainParserFactory;
use Pdp\Rules;
use PHPUnit\Framework\TestCase;

class DomainParserFactoryTest extends TestCase
{
    public function testMake()
    {
        $this->assertInstanceOf(
            Rules::class,
            DomainParserFactory::make()
        );
    }
}
