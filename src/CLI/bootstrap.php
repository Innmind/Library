<?php
declare(strict_types = 1);

namespace CLI;

use function Innmind\InstallationMonitor\bootstrap as monitor;
use Innmind\CLI\Commands;
use Innmind\Socket\Address\Unix as Address;

function bootstrap(): Commands
{
    $monitor = monitor();

    return new Commands(
        new Command\Install(
            $monitor['client']['silence'](
                $monitor['client']['socket'](
                    new Address('/tmp/installation-monitor')
                )
            )
        )
    );
}
