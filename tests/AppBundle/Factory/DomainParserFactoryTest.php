<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Factory;

use AppBundle\Factory\DomainParserFactory;
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
