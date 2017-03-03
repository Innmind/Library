<?php
declare(strict_types = 1);

namespace Tests\Domain\Event\HtmlPage;

use Domain\{
    Event\HtmlPage\AndroidAppLinkSpecified,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class AndroidAppLinkSpecifiedTest extends TestCase
{
    public function testInterface()
    {
        $event = new AndroidAppLinkSpecified(
            $identity = $this->createMock(IdentityInterface::class),
            $url = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $event->identity());
        $this->assertSame($url, $event->url());
    }
}
