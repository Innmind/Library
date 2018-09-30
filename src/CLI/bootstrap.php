<?php
declare(strict_types = 1);

namespace CLI;

use function Innmind\InstallationMonitor\bootstrap as monitor;
use Innmind\CLI\Commands;

function bootstrap(): Commands
{
    $monitor = monitor();

    return new Commands(
        new Command\Install(
            $monitor['client']['silence'](
                $monitor['client']['socket']()
            )
        )
    );
}
