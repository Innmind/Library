<?php
declare(strict_types = 1);

namespace Tests\Web;

use Web\Controllers;
use Innmind\HttpFramework\Controller;
use Innmind\Rest\Server\{
    Controller as RestController,
    Routing\Routes,
    Definition\Loader\YamlLoader,
};
use Innmind\Immutable\MapInterface;
use PHPUnit\Framework\TestCase;

class ControllersTest extends TestCase
{
    public function testFrom()
    {
        $controllers = Controllers::from(
            Routes::from(
                (new YamlLoader)('src/Web/config/rest.yml')
            ),
            $this->createMock(RestController::class),
            $this->createMock(RestController::class),
            $this->createMock(RestController::class),
            $this->createMock(RestController::class),
            $this->createMock(RestController::class),
            $this->createMock(RestController::class),
            $this->createMock(RestController::class),
            $this->createMock(RestController::class),
            function(RestController $controller) {
                return $this->createMock(Controller::class);
            }
        );

        $this->assertInstanceOf(MapInterface::class, $controllers);
        $this->assertSame('string', (string) $controllers->keyType());
        $this->assertSame(Controller::class, (string) $controllers->valueType());
        $this->assertCount(11, $controllers);
    }
}
