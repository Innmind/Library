<?php
declare(strict_types = 1);

namespace Tests\Domain\Command\HtmlPage;

use Domain\{
    Command\HtmlPage\SpecifyAndroidAppLink,
    Entity\HtmlPage\IdentityInterface
};
use Innmind\Url\UrlInterface;

class SpecifyAndroidAppLinkTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $command = new SpecifyAndroidAppLink(
            $identity = $this->createMock(IdentityInterface::class),
            $url = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($identity, $command->identity());
        $this->assertSame($url, $command->url());
    }
}
