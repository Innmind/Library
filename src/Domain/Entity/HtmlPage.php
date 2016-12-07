<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\HtmlPage\IdentityInterface,
    Entity\HttpResource\IdentityInterface as ResourceIdentity,
    Event\HtmlPageRegistered,
    Event\HtmlPage\MainContentSpecified,
    Event\HtmlPage\DescriptionSpecified,
    Event\HtmlPage\AnchorsSpecified,
    Event\HtmlPage\FlaggedAsJournal,
    Event\HtmlPage\ThemeColourSpecified,
    Event\HtmlPage\TitleSpecified,
    Event\HtmlPage\AndroidAppLinkSpecified,
    Event\HtmlPage\IosAppLinkSpecified,
    Exception\InvalidArgumentException
};
use Innmind\Url\{
    PathInterface,
    QueryInterface,
    UrlInterface
};
use Innmind\Colour\RGBA;
use Innmind\Immutable\{
    Set,
    SetInterface
};

final class HtmlPage extends HttpResource
{
    private $mainContent = '';
    private $description = '';
    private $anchors;
    private $isJournal = false;
    private $themeColour;
    private $title = '';
    private $android;
    private $ios;

    public function __construct(
        ResourceIdentity $identity,
        PathInterface $path,
        QueryInterface $query
    ) {
        if (!$identity instanceof IdentityInterface) {
            throw new InvalidArgumentException;
        }

        parent::__construct($identity, $path, $query);
        $this->anchors = new Set('string');
    }

    public static function register(
        ResourceIdentity $identity,
        PathInterface $path,
        QueryInterface $query
    ): HttpResource {
        $self = new self($identity, $path, $query);
        $self->record(new HtmlPageRegistered($identity, $path, $query));

        return $self;
    }

    public function specifyMainContent(string $content): self
    {
        $this->mainContent = $content;
        $this->record(new MainContentSpecified($this->identity(), $content));

        return $this;
    }

    public function mainContent(): string
    {
        return $this->mainContent;
    }

    public function specifyDescription(string $description): self
    {
        $this->description = $description;
        $this->record(new DescriptionSpecified($this->identity(), $description));

        return $this;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function specifyAnchors(SetInterface $anchors): self
    {
        if ((string) $anchors->type() !== 'string') {
            throw new InvalidArgumentException;
        }

        $this->anchors = $anchors;
        $this->record(new AnchorsSpecified($this->identity(), $anchors));

        return $this;
    }

    /**
     * @return SetInterface<string>
     */
    public function anchors(): SetInterface
    {
        return $this->anchors;
    }

    public function flagAsJournal(): self
    {
        $this->isJournal = true;
        $this->record(new FlaggedAsJournal($this->identity()));

        return $this;
    }

    public function isJournal(): bool
    {
        return $this->isJournal;
    }

    public function specifyThemeColour(RGBA $colour): self
    {
        $this->themeColour = $colour;
        $this->record(new ThemeColourSpecified($this->identity(), $colour));

        return $this;
    }

    public function hasThemeColour(): bool
    {
        return $this->themeColour instanceof RGBA;
    }

    public function themeColour(): RGBA
    {
        return $this->themeColour;
    }

    public function specifyTitle(string $title): self
    {
        $this->title = $title;
        $this->record(new TitleSpecified($this->identity(), $title));

        return $this;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function specifyAndroidAppLink(UrlInterface $url): self
    {
        $this->android = $url;
        $this->record(new AndroidAppLinkSpecified($this->identity(), $url));

        return $this;
    }

    public function hasAndroidAppLink(): bool
    {
        return $this->android instanceof UrlInterface;
    }

    public function androidAppLink(): UrlInterface
    {
        return $this->android;
    }

    public function specifyIosAppLink(UrlInterface $url): self
    {
        $this->ios = $url;
        $this->record(new IosAppLinkSpecified($this->identity(), $url));

        return $this;
    }

    public function hasIosAppLink(): bool
    {
        return $this->ios instanceof UrlInterface;
    }

    public function iosAppLink(): UrlInterface
    {
        return $this->ios;
    }
}
