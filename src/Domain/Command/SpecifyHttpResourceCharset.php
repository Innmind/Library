<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\HttpResource\IdentityInterface;

final class SpecifyHttpResourceCharset
{
    private $identity;
    private $charset;

    public function __construct(IdentityInterface $identity, string $charset)
    {
        $this->identity = $identity;
        $this->charset = $charset;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function charset(): string
    {
        return $this->charset;
    }
}
