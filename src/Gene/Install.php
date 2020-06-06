<?php
declare(strict_types = 1);

namespace Library\Gene;

use Innmind\Genome\{
    Gene,
    History,
    History\Event,
    Exception\PreConditionFailed,
    Exception\ExpressionFailed,
};
use Innmind\OperatingSystem\OperatingSystem;
use Innmind\Server\Control\{
    Server,
    Server\Command,
    Server\Script,
    Exception\ScriptFailed,
};
use Innmind\Url\Path;
use Innmind\Immutable\Map;

final class Install implements Gene
{
    private Path $path;

    public function __construct(Path $path)
    {
        $this->path = $path;
    }

    public function name(): string
    {
        return 'Library install';
    }

    public function express(
        OperatingSystem $local,
        Server $target,
        History $history
    ): History {
        try {
            $preCondition = new Script(
                Command::foreground('which')->withArgument('composer'),
            );
            $preCondition($target);
        } catch (ScriptFailed $e) {
            throw new PreConditionFailed('composer is missing');
        }

        $neo4j = $history->get('neo4j.password_changed');

        if ($neo4j->empty()) {
            throw new PreConditionFailed('No neo4j password provided');
        }

        /** @var Event */
        $event = $neo4j->reduce(
            null,
            static fn(?Event $last, Event $event): Event => $event,
        );
        /** @var string */
        $user = $event->payload()->get('user');
        /** @var string */
        $password = $event->payload()->get('password');
        $apiKey = \sha1(\random_bytes(32));

        $dotEnv = <<<DOTENV
        API_KEY=$apiKey
        NEO4J=http://$user:$password@localhost:7474/
        DOTENV;

        try {
            $install = new Script(
                Command::foreground('composer')
                    ->withArgument('create-project')
                    ->withArgument('innmind/library')
                    ->withArgument($this->path->toString())
                    ->withOption('no-dev')
                    ->withOption('prefer-source')
                    ->withOption('keep-vcs'),
                Command::foreground('echo')
                    ->withArgument($dotEnv)
                    ->overwrite($this->path->resolve(Path::of('config/.env'))),
            );
            $install($target);
        } catch (ScriptFailed $e) {
            throw new ExpressionFailed($this->name());
        }

        /** @var Map<string, mixed> */
        $payload = Map::of('string', 'mixed');

        return $history
            ->add(
                'website_available', // useful for infrastructure-nginx
                $payload
                    ('path', $this->path->resolve(Path::of('public'))->toString())
                    ('php_version', '7.4')
                    ('name', 'library'),
            )
            ->add(
                'library_installed', // useful for crawler-app
                $payload
                    ('apiKey', $apiKey),
            );
    }
}
