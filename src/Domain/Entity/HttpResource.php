<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\HttpResource\Identity,
    Entity\HttpResource\Charset,
    Event\HttpResourceRegistered,
    Event\HttpResource\LanguagesSpecified,
    Event\HttpResource\CharsetSpecified,
    Model\Language,
    Exception\DomainException,
};
use Innmind\EventBus\{
    ContainsRecordedEvents,
    EventRecorder,
};
use Innmind\Url\{
    Path,
    Query,
};
use Innmind\Immutable\Set;

class HttpResource implements ContainsRecordedEvents
{
    use EventRecorder;

    private Identity $identity;
    private Path $path;
    private Query $query;
    private Set $languages;
    private ?Charset $charset = null;

    public function __construct(
        Identity $identity,
        Path $path,
        Query $query
    ) {
        $this->identity = $identity;
        $this->path = $path;
        $this->query = $query;
        $this->languages = Set::of(Language::class);
    }

    public static function register(
        Identity $identity,
        Path $path,
        Query $query
    ): self {
        $self = new self($identity, $path, $query);
        $self->record(new HttpResourceRegistered($identity, $path, $query));

        return $self;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function path(): Path
    {
        return $this->path;
    }

    public function query(): Query
    {
        return $this->query;
    }

    public function specifyLanguages(Set $languages): self
    {
        if ((string) $languages->type() !== Language::class) {
            throw new \TypeError(sprintf(
                'Argument 1 must be of type Set<%s>',
                Language::class
            ));
        }

        if ($languages->size() === 0) {
            throw new DomainException;
        }

        $this->languages = $languages;
        $this->record(new LanguagesSpecified($this->identity, $languages));

        return $this;
    }

    public function languages(): Set
    {
        return $this->languages;
    }

    public function specifyCharset(Charset $charset): self
    {
        $this->charset = $charset;
        $this->record(new CharsetSpecified($this->identity, $charset));

        return $this;
    }

    public function hasCharset(): bool
    {
        return $this->charset instanceof Charset;
    }

    public function charset(): Charset
    {
        return $this->charset;
    }
}
