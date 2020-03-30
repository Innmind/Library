<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Citation;

final class CitationAlreadyExist extends LogicException
{
    private Citation $citation;

    public function __construct(Citation $citation)
    {
        $this->citation = $citation;
    }

    /**
     * The citation that already exist
     */
    public function citation(): Citation
    {
        return $this->citation;
    }
}
