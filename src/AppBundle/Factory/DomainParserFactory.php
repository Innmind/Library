<?php
declare(strict_types = 1);

namespace AppBundle\Factory;

use Pdp\{
    Parser,
    PublicSuffixListManager
};

final class DomainParserFactory
{
    public static function make(): Parser
    {
        return new Parser(
            (new PublicSuffixListManager)->getList()
        );
    }
}
