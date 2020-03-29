<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Reference;

final class ReferenceAlreadyExist extends LogicException
{
    private Reference $reference;

    public function __construct(Reference $reference)
    {
        $this->reference = $reference;
        parent::__construct();
    }

    /**
     * The reference that already exist
     */
    public function reference(): Reference
    {
        return $this->reference;
    }
}
