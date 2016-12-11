<?php
declare(strict_types = 1);

namespace Domain\Entity;

use Domain\{
    Entity\HttpResource\IdentityInterface,
    Entity\HttpResource\Charset,
    Event\HttpResourceRegistered,
    Event\HttpResource\LanguagesSpecified,
    Event\HttpResource\CharsetSpecified,
    Model\Language,
    Exception\InvalidArgumentException
};
use Innmind\EventBus\{
    ContainsRecordedEventsInterface,
    EventRecorder
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Immutable\{
    Set,
    SetInterface
};

class HttpResource implements ContainsRecordedEventsInterface
{
    use EventRecorder;

    private $identity;
    private $path;
    private $query;
    private $languages;
    private $charset;

    public function __construct(
        IdentityInterface $identity,
        PathInterface $path,
        QueryInterface $query
    ) {
        $this->identity = $identity;
        $this->path = $path;
        $this->query = $query;
        $this->languages = new Set('string');
    }

    public static function register(
        IdentityInterface $identity,
        PathInterface $path,
        QueryInterface $query
    ): self {
        $self = new self($identity, $path, $query);
        $self->record(new HttpResourceRegistered($identity, $path, $query));

        return $self;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function query(): QueryInterface
    {
        return $this->query;
    }

    public function specifyLanguages(SetInterface $languages): self
    {
        if (
            (string) $languages->type() !== Language::class ||
            $languages->size() === 0
        ) {
            throw new InvalidArgumentException;
        }

        $this->languages = $languages;
        $this->record(new LanguagesSpecified($this->identity, $languages));

        return $this;
    }

    public function languages(): SetInterface
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
