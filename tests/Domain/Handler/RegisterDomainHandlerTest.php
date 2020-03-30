<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterDomainHandler,
    Command\RegisterDomain,
    Entity\Domain,
    Entity\Domain\Identity,
    Entity\Domain\Name as NameModel,
    Entity\Domain\TopLevelDomain as TLD,
    Repository\DomainRepository,
    Specification\AndSpecification,
    Specification\Domain\Name,
    Specification\Domain\TopLevelDomain,
    Exception\DomainAlreadyExist,
};
use Domain\Specification;
use Innmind\Url\Authority\Host;
use Innmind\Immutable\Set;
use Pdp\{
    Rules,
    Manager,
    Cache,
    CurlHttpClient,
};
use PHPUnit\Framework\TestCase;

class RegisterDomainHandlerTest extends TestCase
{
    public function testCreateDomain()
    {
        $handler = new RegisterDomainHandler(
            $repository = $this->createMock(DomainRepository::class),
            (new Manager(new Cache, new CurlHttpClient))->getRules()
        );
        $command = new RegisterDomain(
            $this->createMock(Identity::class),
            Host::of('www.example.co.uk')
        );

        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Name &&
                    $spec->right() instanceof TopLevelDomain &&
                    $spec->left()->value() === 'example' &&
                    $spec->right()->value() === 'co.uk';
            }))
            ->willReturn(Set::of(Domain::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Domain $domain) use ($command): bool {
                return (string) $domain === 'example.co.uk' &&
                    $domain->identity() === $command->identity();
            }));

        $this->assertNull($handler($command));
    }

    public function testThrowWhenDomainAlreadyExist()
    {
        $handler = new RegisterDomainHandler(
            $repository = $this->createMock(DomainRepository::class),
            (new Manager(new Cache, new CurlHttpClient))->getRules()
        );
        $command = new RegisterDomain(
            $this->createMock(Identity::class),
            Host::of('www.example.co.uk')
        );

        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Name &&
                    $spec->right() instanceof TopLevelDomain &&
                    $spec->left()->value() === 'example' &&
                    $spec->right()->value() === 'co.uk';
            }))
            ->willReturn(
                Set::of(
                    Domain::class,
                    new Domain(
                        $this->createMock(Identity::class),
                        new NameModel('foo'),
                        new TLD('fr')
                    )
                )
            );
        $repository
            ->expects($this->never())
            ->method('add');

        $this->expectException(DomainAlreadyExist::class);

        $handler($command);
    }
}
