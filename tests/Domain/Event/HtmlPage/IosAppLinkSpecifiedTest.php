<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\IosAppLinkSpecified,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Url\UrlInterface;

class IosAppLinkSpecifiedTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $event = new IosAppLinkSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $url = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($url, $event->url());
    }
}
