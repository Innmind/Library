<?php
declare(strict_types = 1);

namespace CLI\Command;

use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\InstallationMonitor\{
    Client,
    Event,
};
use Innmind\Immutable\{
    Map,
    Str,
    SequenceInterface,
    Sequence,
};

final class Install implements Command
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(Environment $env, Arguments $arguments, Options $options): void
    {
        $envFile = $env->workingDirectory().'/config/.env';

        if (file_exists($envFile)) {
            $env->error()->write(
                Str::of("App already installed\n")
            );
            $env->exit(1);

            return;
        }

        $envVars = (new Map('string', 'string'))
            ->put('API_KEY', \sha1(\random_bytes(32)));

        $passwords = $this
            ->client
            ->events()
            ->filter(static function(Event $event): bool {
                return (string) $event->name() === 'neo4j.password_changed';
            });

        if ($passwords->size() !== 1) {
            $env->error()->write(
                Str::of("Neo4j password can't be determined\n")
            );
            $env->exit(1);

            return;
        }

        $event = $passwords->current()->payload();
        $user = $event->get('user');
        $password = $event->get('password');

        $envVars = $envVars->put(
            'NEO4J',
            "http://$user:$password@localhost:7474/"
        );

        file_put_contents(
            $envFile,
            (string) $envVars
                ->reduce(
                    new Sequence,
                    static function(SequenceInterface $lines, string $key, string $value): SequenceInterface {
                        return $lines->add(sprintf(
                            '%s=%s',
                            $key,
                            $value
                        ));
                    }
                )
                ->join("\n")
        );

        $this->client->send(
            new Event(
                new Event\Name('website_available'), // useful for infrastructure-nginx
                (new Map('string', 'variable'))
                    ->put('path', $env->workingDirectory().'/public')
            ),
            new Event(
                new Event\Name('library_installed'), // useful for crawler-app
                (new Map('string', 'variable'))
                    ->put('apiKey', $envVars->get('API_KEY'))
            )
        );
    }

    public function __toString(): string
    {
        return <<<USAGE
install

This will configure the config/.env file

It will do so by reading events recorded by the installation monitor
USAGE;
    }
}
