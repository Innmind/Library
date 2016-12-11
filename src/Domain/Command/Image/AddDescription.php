<?php
declare(strict_types = 1);

namespace Domain\Command\Image;

use Domain\Entity\Image\{
    IdentityInterface,
    Description
};

final class AddDescription
{
    private $identity;
    private $description;

    public function __construct(
        IdentityInterface $identity,
        Description $description
    ) {
        $this->identity = $identity;
        $this->description = $description;
    }

    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

    public function description(): Description
    {
        return $this->description;
    }
}
