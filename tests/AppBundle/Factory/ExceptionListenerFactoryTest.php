<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Factory;

use AppBundle\{
    Factory\ExceptionListenerFactory,
    EventListener\ExceptionListener
};
use PHPUnit\Framework\TestCase;

class ExceptionListenerFactoryTest extends TestCase
{
    public function testMake()
    {
        $listener = ExceptionListenerFactory::make([
            'stdClass' => 'Exception'
        ]);

        $this->assertInstanceOf(ExceptionListener::class, $listener);
    }
}
