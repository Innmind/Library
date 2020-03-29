<?php
declare(strict_types = 1);

namespace Domain\Event\HttpResource;

use Domain\Entity\HttpResource\{
    Identity,
    Charset
};

final class CharsetSpecified
{
    private Identity $identity;
    private Charset $charset;

    public function __construct(Identity $identity, Charset $charset)
    {
        $this->identity = $identity;
        $this->charset = $charset;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function charset(): Charset
    {
        return $this->charset;
    }
}
