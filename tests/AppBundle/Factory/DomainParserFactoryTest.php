<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Factory;

use AppBundle\Factory\DomainParserFactory;
use Pdp\Parser;
use PHPUnit\Framework\TestCase;

class DomainParserFactoryTest extends TestCase
{
    public function testMake()
    {
        $this->assertInstanceOf(
            Parser::class,
            DomainParserFactory::make()
        );
    }
}
