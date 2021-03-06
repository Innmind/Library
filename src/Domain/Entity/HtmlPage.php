<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
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
use function Innmind\Immutable\assertSet;

final class HtmlPage extends HttpResource
{
    private string $mainContent = '';
    private string $description = '';
    /** @var Set<Anchor> */
    private Set $anchors;
    private bool $isJournal = false;
    private ?RGBA $themeColour = null;
    private string $title = '';
    private ?Url $android = null;
    private ?Url $ios = null;
    private ?Url $preview = null;

    public function __construct(
        ResourceIdentity $identity,
        Path $path,
        Query $query
    ) {
        if (!$identity instanceof Identity) {
            throw new InvalidArgumentException;
        }

        parent::__construct($identity, $path, $query);
        /** @var Set<Anchor> */
        $this->anchors = Set::of(Anchor::class);
    }

    public static function register(
        ResourceIdentity $identity,
        Path $path,
        Query $query
    ): self {
        $self = new self($identity, $path, $query);
        /** @psalm-suppress ArgumentTypeCoercion */
        $self->record(new HtmlPageRegistered($identity, $path, $query));

        return $self;
    }

    /** @psalm-suppress MoreSpecificReturnType */
    public function identity(): Identity
    {
        /** @psalm-suppress LessSpecificReturnStatement */
        return parent::identity();
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

    /**
     * @param Set<Anchor> $anchors
     */
    public function specifyAnchors(Set $anchors): self
    {
        assertSet(Anchor::class, $anchors, 1);

        $this->anchors = $anchors;
        $this->record(new AnchorsSpecified($this->identity(), $anchors));

        return $this;
    }

    /**
     * @return Set<Anchor>
     */
    public function anchors(): Set
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

    /** @psalm-suppress InvalidNullableReturnType */
    public function themeColour(): RGBA
    {
        /** @psalm-suppress NullableReturnStatement */
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

    public function specifyAndroidAppLink(Url $url): self
    {
        $this->android = $url;
        $this->record(new AndroidAppLinkSpecified($this->identity(), $url));

        return $this;
    }

    public function hasAndroidAppLink(): bool
    {
        return $this->android instanceof Url;
    }

    /** @psalm-suppress InvalidNullableReturnType */
    public function androidAppLink(): Url
    {
        /** @psalm-suppress NullableReturnStatement */
        return $this->android;
    }

    public function specifyIosAppLink(Url $url): self
    {
        $this->ios = $url;
        $this->record(new IosAppLinkSpecified($this->identity(), $url));

        return $this;
    }

    public function hasIosAppLink(): bool
    {
        return $this->ios instanceof Url;
    }

    /** @psalm-suppress InvalidNullableReturnType */
    public function iosAppLink(): Url
    {
        /** @psalm-suppress NullableReturnStatement */
        return $this->ios;
    }

    public function usePreview(Url $preview): self
    {
        $this->preview = $preview;
        $this->record(new PreviewSpecified($this->identity(), $preview));

        return $this;
    }

    public function hasPreview(): bool
    {
        return $this->preview instanceof Url;
    }

    /** @psalm-suppress InvalidNullableReturnType */
    public function preview(): Url
    {
        /** @psalm-suppress NullableReturnStatement */
        return $this->preview;
    }
}
