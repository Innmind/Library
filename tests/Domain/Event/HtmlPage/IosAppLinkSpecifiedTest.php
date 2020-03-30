<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\IosAppLinkSpecified,
    Entity\HtmlPage\Identity
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class IosAppLinkSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new IosAppLinkSpecified(
            $identity = $this->createMock(Identity::class),
            $url = Url::of('example.com')
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($url, $event->url());
    }
}
