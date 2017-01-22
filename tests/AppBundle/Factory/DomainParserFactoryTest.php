<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Factory;

use AppBundle\Factory\DomainParserFactory;
use Pdp\Parser;

class DomainParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $this->assertInstanceOf(
            Parser::class,
            DomainParserFactory::make()
        );
    }
}
