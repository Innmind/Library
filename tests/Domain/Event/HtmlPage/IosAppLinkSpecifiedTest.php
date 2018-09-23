<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\IosAppLinkSpecified,
    Entity\HtmlPage\Identity
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class IosAppLinkSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new IosAppLinkSpecified(
            $identity = $this->createMock(Identity::class),
            $url = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($url, $event->url());
    }
}
