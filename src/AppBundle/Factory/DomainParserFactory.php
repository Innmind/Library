<?php
declare(strict_types = 1);

namespace AppBundle\Factory;

use Pdp\{
    Rules,
    Manager,
    Cache,
    CurlHttpClient
};

final class DomainParserFactory
{
    public static function make(): Rules
    {
        return (new Manager(new Cache, new CurlHttpClient))->getRules();
    }
}
