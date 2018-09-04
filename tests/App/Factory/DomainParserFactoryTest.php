<?php
declare(strict_types = 1);

namespace Tests\App\Factory;

use App\Factory\DomainParserFactory;
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
