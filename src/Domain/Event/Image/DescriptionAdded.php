<?php
declare(strict_types = 1);

namespace Domain\Event\Image;

use Domain\Entity\Image\{
    Identity,
    Description
};

final class DescriptionAdded
{
    private Identity $identity;
    private Description $description;

    public function __construct(
        Identity $identity,
        Description $description
    ) {
        $this->identity = $identity;
        $this->description = $description;
    }

    public function identity(): Identity
    {
        return $this->identity;
    }

    public function description(): Description
    {
        return $this->description;
    }
}
