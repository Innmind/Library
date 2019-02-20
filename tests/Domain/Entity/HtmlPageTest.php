<?php
declare(strict_types = 1);

namespace Tests\Domain\Entity;

use Domain\{
    Entity\HtmlPage,
    Entity\HttpResource,
    Entity\HtmlPage\Identity,
    Entity\HtmlPage\Anchor,
    Entity\HttpResource\Identity as ResourceIdentity,
    Event\HtmlPageRegistered,
    Event\HtmlPage\MainContentSpecified,
    Event\HtmlPage\DescriptionSpecified,
    Event\HtmlPage\AnchorsSpecified,
    Event\HtmlPage\FlaggedAsJournal,
    Event\HtmlPage\ThemeColourSpecified,
    Event\HtmlPage\TitleSpecified,
    Event\HtmlPage\AndroidAppLinkSpecified,
    Event\HtmlPage\IosAppLinkSpecified,
    Event\HtmlPage\PreviewSpecified,
};
use Innmind\Url\{
    PathInterface,
    QueryInterface,
    UrlInterface,
};
use Innmind\Colour\RGBA;
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class HtmlPageTest extends TestCase
{
    public function testInterface()
    {
        $htmlPage = new HtmlPage(
            $identity = $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertInstanceOf(HttpResource::class, $htmlPage);
        $this->assertSame($identity, $htmlPage->identity());
        $this->assertSame($path, $htmlPage->path());
        $this->assertSame($query, $htmlPage->query());
        $this->assertFalse($htmlPage->hasThemeColour());
        $this->assertFalse($htmlPage->hasAndroidAppLink());
        $this->assertFalse($htmlPage->hasIosAppLink());
        $this->assertCount(0, $htmlPage->anchors());
        $this->assertCount(0, $htmlPage->recordedEvents());
    }

    /**
     * @expectedException Domain\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidIdentity()
    {
        new HtmlPage(
            $this->createMock(ResourceIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
    }

    public function testRegister()
    {
        $htmlPage = HtmlPage::register(
            $identity = $this->createMock(Identity::class),
            $path = $this->createMock(PathInterface::class),
            $query = $this->createMock(QueryInterface::class)
        );

        $this->assertInstanceOf(HtmlPage::class, $htmlPage);
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            HtmlPageRegistered::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $identity,
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $path,
            $htmlPage->recordedEvents()->current()->path()
        );
        $this->assertSame(
            $query,
            $htmlPage->recordedEvents()->current()->query()
        );
    }

    public function testSpecifyMainContent()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame('', $htmlPage->mainContent());
        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyMainContent('foo')
        );
        $this->assertSame('foo', $htmlPage->mainContent());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            MainContentSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->mainContent(),
            $htmlPage->recordedEvents()->current()->mainContent()
        );
    }

    public function testSpecifyDescription()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame('', $htmlPage->description());
        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyDescription('foo')
        );
        $this->assertSame('foo', $htmlPage->description());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            DescriptionSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->description(),
            $htmlPage->recordedEvents()->current()->description()
        );
    }

    public function testSpecifyAnchors()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertCount(0, $htmlPage->anchors());
        $this->assertSame(Anchor::class, (string) $htmlPage->anchors()->type());
        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyAnchors(
                $anchors = (new Set(Anchor::class))
                    ->add(new Anchor('foo'))
            )
        );
        $this->assertSame($anchors, $htmlPage->anchors());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            AnchorsSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->anchors(),
            $htmlPage->recordedEvents()->current()->anchors()
        );
    }

    /**
     * @expectedException TypeError
     * @expectedExceptionMessage Argument 1 must be of type SetInterface<Domain\Entity\HtmlPage\Anchor>
     */
    public function testThrowWhenInvalidAnchorsSet()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $htmlPage->specifyAnchors(new Set('int'));
    }

    public function testFlagAsJournal()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertFalse($htmlPage->isJournal());
        $this->assertSame(
            $htmlPage,
            $htmlPage->flagAsJournal()
        );
        $this->assertTrue($htmlPage->isJournal());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            FlaggedAsJournal::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
    }

    public function testSpecifyThemeColour()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyThemeColour(
                $colour = RGBA::fromString('39f')
            )
        );
        $this->assertSame($colour, $htmlPage->themeColour());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            ThemeColourSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->themeColour(),
            $htmlPage->recordedEvents()->current()->colour()
        );
    }

    public function testSpecifyTitle()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame('', $htmlPage->title());
        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyTitle('foo')
        );
        $this->assertSame('foo', $htmlPage->title());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            TitleSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->title(),
            $htmlPage->recordedEvents()->current()->title()
        );
    }

    public function testSpecifyAndroidAppLink()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyAndroidAppLink(
                $url = $this->createMock(UrlInterface::class)
            )
        );
        $this->assertSame($url, $htmlPage->androidAppLink());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            AndroidAppLinkSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->androidAppLink(),
            $htmlPage->recordedEvents()->current()->url()
        );
    }

    public function testSpecifyIosAppLink()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyIosAppLink(
                $url = $this->createMock(UrlInterface::class)
            )
        );
        $this->assertSame($url, $htmlPage->iosAppLink());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            IosAppLinkSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->iosAppLink(),
            $htmlPage->recordedEvents()->current()->url()
        );
    }

    public function testUsePreview()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );

        $this->assertFalse($htmlPage->hasPreview());
        $this->assertSame(
            $htmlPage,
            $htmlPage->usePreview($url = $this->createMock(UrlInterface::class))
        );
        $this->assertTrue($htmlPage->hasPreview());
        $this->assertSame($url, $htmlPage->preview());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            PreviewSpecified::class,
            $htmlPage->recordedEvents()->current()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->current()->identity()
        );
        $this->assertSame(
            $htmlPage->preview(),
            $htmlPage->recordedEvents()->current()->url()
        );
    }
}
