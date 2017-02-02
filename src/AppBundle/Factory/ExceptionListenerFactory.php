<?php
declare(strict_types = 1);

namespace AppBundle\Factory;

use AppBundle\EventListener\ExceptionListener;
use Innmind\Immutable\Map;

final class ExceptionListenerFactory
{
    public static function make(array $exceptions): ExceptionListener
    {
        $map = new Map('string', 'string');

        foreach ($exceptions as $from => $to) {
            $map = $map->put($from, $to);
        }

        return new ExceptionListener($map);
    }
}
