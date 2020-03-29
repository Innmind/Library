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
use Innmind\Url\Path;
use Innmind\Immutable\{
    Map,
    Str,
    Sequence,
};
use function Innmind\Immutable\join;

final class Install implements Command
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(Environment $env, Arguments $arguments, Options $options): void
    {
        $envFile = $env->workingDirectory()->toString().'config/.env';

        if (file_exists($envFile)) {
            $env->error()->write(
                Str::of("App already installed\n")
            );
            $env->exit(1);

            return;
        }

        $envVars = Map::of('string', 'string')
            ('API_KEY', \sha1(\random_bytes(32)));

        $passwords = $this
            ->client
            ->events()
            ->filter(static function(Event $event): bool {
                return $event->name()->toString() === 'neo4j.password_changed';
            });

        if ($passwords->size() !== 1) {
            $env->error()->write(
                Str::of("Neo4j password can't be determined\n")
            );
            $env->exit(1);

            return;
        }

        $event = $passwords->first()->payload();
        $user = $event->get('user');
        $password = $event->get('password');

        $envVars = $envVars->put(
            'NEO4J',
            "http://$user:$password@localhost:7474/"
        );

        file_put_contents(
            $envFile,
            join(
                "\n",
                $envVars
                    ->reduce(
                        Sequence::strings(),
                        static function(Sequence $lines, string $key, string $value): Sequence {
                            return $lines->add(sprintf(
                                '%s=%s',
                                $key,
                                $value
                            ));
                        }
                    )
            )->toString(),
        );

        $this->client->send(
            new Event(
                new Event\Name('website_available'), // useful for infrastructure-nginx
                Map::of('string', 'scalar|array')
                    ('path', $env->workingDirectory()->resolve(Path::of('public'))->toString())
            ),
            new Event(
                new Event\Name('library_installed'), // useful for crawler-app
                Map::of('string', 'scalar|array')
                    ('apiKey', $envVars->get('API_KEY'))
            )
        );
    }

    public function toString(): string
    {
        return <<<USAGE
install

This will configure the config/.env file

It will do so by reading events recorded by the installation monitor
USAGE;
    }
}
