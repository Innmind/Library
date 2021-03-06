<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\CitationAppearance;

final class CitationAppearanceAlreadyExist extends LogicException
{
    private CitationAppearance $appearance;

    public function __construct(CitationAppearance $appearance)
    {
        $this->appearance = $appearance;
    }

    /**
     * The appearance that already exist
     */
    public function appearance(): CitationAppearance
    {
        return $this->appearance;
    }
}
