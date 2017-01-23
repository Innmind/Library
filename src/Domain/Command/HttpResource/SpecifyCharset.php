<?php
declare(strict_types = 1);

namespace Domain\Command\HttpResource;

use Domain\Entity\HttpResource\{
    IdentityInterface,
    Charset
};

final class SpecifyCharset
{
    private $identity;
    private $charset;

    public function __construct(IdentityInterface $identity, Charset $charset)
    {
        $this->identity = $identity;
        $this->charset = $charset;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function charset(): Charset
    {
        return $this->charset;
    }
}
