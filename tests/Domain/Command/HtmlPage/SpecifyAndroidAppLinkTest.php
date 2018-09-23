<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyAndroidAppLink,
    Entity\HtmlPage\Identity
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class SpecifyAndroidAppLinkTest extends TestCase
{
    public function testInterface()
    {
        $command = new SpecifyAndroidAppLink(
            $identity = $this->createMock(Identity::class),
            $url = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($url, $command->url());
    }
}
