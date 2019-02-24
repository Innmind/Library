<?php
declare(strict_types = 1);

namespace CLI;

use function Innmind\InstallationMonitor\bootstrap as monitor;
use Innmind\CLI\Commands;
use Innmind\OperatingSystem\OperatingSystem;

function bootstrap(OperatingSystem $os): Commands
{
    $monitor = monitor($os);

    return new Commands(
        new Command\Install(
            $monitor['client']['silence'](
                $monitor['client']['ipc']()
            )
        )
    );
}
