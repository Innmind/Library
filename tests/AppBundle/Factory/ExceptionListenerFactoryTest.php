<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Factory;

use AppBundle\{
    Factory\ExceptionListenerFactory,
    EventListener\ExceptionListener
};

class ExceptionListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $listener = ExceptionListenerFactory::make([
            'stdClass' => 'Exception'
        ]);

        $this->assertInstanceOf(ExceptionListener::class, $listener);
    }
}
