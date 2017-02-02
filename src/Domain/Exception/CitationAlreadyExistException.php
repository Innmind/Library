<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Citation;

final class CitationAlreadyExistException extends LogicException
{
    private $citation;

    public function __construct(Citation $citation)
    {
        $this->citation = $citation;
        parent::__construct();
    }

    /**
     * The citation that already exist
     */
    public function citation(): Citation
    {
        return $this->citation;
    }
}
