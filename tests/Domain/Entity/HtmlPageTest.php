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
    Exception\InvalidArgumentException,
};
use Innmind\Url\{
    Path,
    Query,
    Url,
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
            $path = Path::none(),
            $query = Query::none()
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

    public function testThrowWhenInvalidIdentity()
    {
        $this->expectException(InvalidArgumentException::class);

        new HtmlPage(
            $this->createMock(ResourceIdentity::class),
            Path::none(),
            Query::none()
        );
    }

    public function testRegister()
    {
        $htmlPage = HtmlPage::register(
            $identity = $this->createMock(Identity::class),
            $path = Path::none(),
            $query = Query::none()
        );

        $this->assertInstanceOf(HtmlPage::class, $htmlPage);
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            HtmlPageRegistered::class,
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $identity,
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $path,
            $htmlPage->recordedEvents()->first()->path()
        );
        $this->assertSame(
            $query,
            $htmlPage->recordedEvents()->first()->query()
        );
    }

    public function testSpecifyMainContent()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->mainContent(),
            $htmlPage->recordedEvents()->first()->mainContent()
        );
    }

    public function testSpecifyDescription()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->description(),
            $htmlPage->recordedEvents()->first()->description()
        );
    }

    public function testSpecifyAnchors()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $this->assertCount(0, $htmlPage->anchors());
        $this->assertSame(Anchor::class, (string) $htmlPage->anchors()->type());
        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyAnchors(
                $anchors = (Set::of(Anchor::class))
                    ->add(new Anchor('foo'))
            )
        );
        $this->assertSame($anchors, $htmlPage->anchors());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            AnchorsSpecified::class,
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->anchors(),
            $htmlPage->recordedEvents()->first()->anchors()
        );
    }

    public function testThrowWhenInvalidAnchorsSet()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 1 must be of type Set<Domain\Entity\HtmlPage\Anchor>');

        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $htmlPage->specifyAnchors(Set::of('int'));
    }

    public function testFlagAsJournal()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
    }

    public function testSpecifyThemeColour()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyThemeColour(
                $colour = RGBA::of('39f')
            )
        );
        $this->assertSame($colour, $htmlPage->themeColour());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            ThemeColourSpecified::class,
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->themeColour(),
            $htmlPage->recordedEvents()->first()->colour()
        );
    }

    public function testSpecifyTitle()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
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
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->title(),
            $htmlPage->recordedEvents()->first()->title()
        );
    }

    public function testSpecifyAndroidAppLink()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyAndroidAppLink(
                $url = Url::of('http://example.com')
            )
        );
        $this->assertSame($url, $htmlPage->androidAppLink());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            AndroidAppLinkSpecified::class,
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->androidAppLink(),
            $htmlPage->recordedEvents()->first()->url()
        );
    }

    public function testSpecifyIosAppLink()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $this->assertSame(
            $htmlPage,
            $htmlPage->specifyIosAppLink(
                $url = Url::of('http://example.com')
            )
        );
        $this->assertSame($url, $htmlPage->iosAppLink());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            IosAppLinkSpecified::class,
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->iosAppLink(),
            $htmlPage->recordedEvents()->first()->url()
        );
    }

    public function testUsePreview()
    {
        $htmlPage = new HtmlPage(
            $this->createMock(Identity::class),
            Path::none(),
            Query::none()
        );

        $this->assertFalse($htmlPage->hasPreview());
        $this->assertSame(
            $htmlPage,
            $htmlPage->usePreview($url = Url::of('http://example.com'))
        );
        $this->assertTrue($htmlPage->hasPreview());
        $this->assertSame($url, $htmlPage->preview());
        $this->assertCount(1, $htmlPage->recordedEvents());
        $this->assertInstanceOf(
            PreviewSpecified::class,
            $htmlPage->recordedEvents()->first()
        );
        $this->assertSame(
            $htmlPage->identity(),
            $htmlPage->recordedEvents()->first()->identity()
        );
        $this->assertSame(
            $htmlPage->preview(),
            $htmlPage->recordedEvents()->first()->url()
        );
    }
}
