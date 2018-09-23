<?php
declare(strict_types = 1);

namespace Domain\Command\Image;

use Domain\Entity\Image\{
    Identity,
    Description
};

final class AddDescription
{
    private $identity;
    private $description;

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
