<?php
declare(strict_types = 1);

namespace Domain\Command;

use Domain\Entity\Citation\{
    Identity,
    Text
};

final class RegisterCitation
{
    private Identity $identity;
    private Text $text;

    public function __construct(Identity $identity, Text $text)
    {
        $this->identity = $identity;
        $this->text = $text;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function text(): Text
    {
        return $this->text;
    }
}
