<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Alternate;

final class AlternateAlreadyExist extends LogicException
{
    private Alternate $alternate;

    public function __construct(Alternate $alternate)
    {
        $this->alternate = $alternate;
        parent::__construct();
    }

    /**
     * The alternate that already exist
     */
    public function alternate(): Alternate
    {
        return $this->alternate;
    }
}
