<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Canonical;

final class CanonicalAlreadyExist extends LogicException
{
    private $canonical;

    public function __construct(Canonical $canonical)
    {
        $this->canonical = $canonical;
        parent::__construct();
    }

    /**
     * The canonical that already exist
     */
    public function canonical(): Canonical
    {
        return $this->canonical;
    }
}
