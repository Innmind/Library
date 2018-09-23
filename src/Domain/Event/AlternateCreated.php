<?php
declare(strict_types = 1);

namespace Domain\Event;

use Domain\{
    Entity\Alternate\Identity,
    Entity\HttpResource\Identity as ResourceIdentity,
    Model\Language
};

final class AlternateCreated
{
    private $identity;
    private $resource;
    private $alternate;
    private $language;

    public function __construct(
        Identity $identity,
        ResourceIdentity $resource,
        ResourceIdentity $alternate,
        Language $language
    ) {
        $this->identity = $identity;
        $this->resource = $resource;
        $this->alternate = $alternate;
        $this->language = $language;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function resource(): ResourceIdentity
    {
        return $this->resource;
    }

    public function alternate(): ResourceIdentity
    {
        return $this->alternate;
    }

    public function language(): Language
    {
        return $this->language;
    }
}
